<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 17.06.2018
 * Time: 9:19
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Rover\Fadmin\Options;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\SystemException;

/**
 * Class Tabcontrol
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Tabcontrol extends Input
{
    /** @var array */
    protected $tabs = array();

    /** @var array */
    protected $tabsConfig = array();

    /** @var array */
    protected $presetMap = array();

    /**
     * Tabcontrol constructor.
     *
     * @param array   $tabsConfig
     * @param Options $options
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public function __construct(array $tabsConfig = array(), Options $options)
    {
        $params = array(
            'name'  => $this->getModuleId() . '_' . self::getType(),
            'label' => $this->getModuleId() . '_' . self::getType(),
        );

        parent::__construct($params, $options);

        $this->tabsConfig = $tabsConfig;
    }

    /**
     * @param bool $reload
     * @return mixed|null
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getTabs($reload = false)
    {
        if (!count($this->tabs) || $reload)
            $this->reloadTabs();

        return $this->optionsEngine->event
            ->handle(Options\Event::AFTER_GET_TABS, array('tabs' => $this->tabs))
            ->getParameter('tabs');
    }

    /**
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function reloadTabs()
    {
        $this->tabs      = array();
        $this->presetMap = array();

        foreach ($this->tabsConfig as $tabParams) {

            if (empty($tabParams))
                continue;

            if (isset($tabParams['preset']) && $tabParams['preset']) {

                $siteId = $tabParams['siteId'] ?: '';
                // preset tab can be only one on current site
                if (isset($this->presetMap[$siteId]))
                    continue;

                $this->presetMap[$siteId] = true;

                $presets = $this->optionsEngine->preset->getList($siteId);

                if (is_array($presets) && count($presets)) {
                    foreach ($presets as $preset) {

                        // event before create preset tab
                        if (!$this->optionsEngine->event
                            ->handle(Options\Event::BEFORE_MAKE_PRESET_TAB, array(
                                'tabParams' => $tabParams,
                                'presetId'  => $preset['id'],
                                'presetName'=> $preset['name']
                            ))->isSuccess())
                            continue;

                        $resultTabParams            = $this->optionsEngine->event->getParameter('tabParams');
                        $resultTabParams['presetId']= $this->optionsEngine->event->getParameter('presetId');
                        $resultTabParams['label']   = $this->optionsEngine->event->getParameter('presetName');

                        $tab = new Tab($resultTabParams, $this->optionsEngine);

                        $this->tabs[] = $this->optionsEngine->event
                            ->handle(Options\Event::AFTER_MAKE_PRESET_TAB, compact('tab'))
                            ->getParameter('tab');
                    }
                }

            } else {
                $this->tabs[] = new Tab($tabParams, $this->optionsEngine);
            }
        }
    }

    /**
     * @param        $presetId
     * @param string $siteId
     * @param bool   $reload
     * @return mixed|null|Tab
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getTabByPresetId($presetId, $siteId = '', $reload = false)
    {
        $presetId = intval($presetId);
        if (!$presetId)
            throw new ArgumentNullException('presetId');

        $tabs = $this->getTabs($reload);

        foreach ($tabs as $tab)
            /** @var Tab $tab */
            if ($tab->isPreset()
                && ($tab->getPresetId() == $presetId)
                && ($tab->getSiteId() == $siteId))
                return $tab;

        return null;
    }

    /**
     * @param array $filter
     * @return array|mixed|null
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function search(array $filter)
    {
        $result = array();
        $tabs   = $this->getTabs();
        $tabsCnt= count($tabs);

        for ($i = 0; $i < $tabsCnt; ++$i) {
            /** @var Tab $tab */
            $tab = $tabs[$i];
            $result[] = $tab->search($filter);
        }

        $resultCnt = count($result);

        if ($resultCnt == 1)
            return reset($result);

        if ($resultCnt > 1)
            return $result;

        return null;
    }
}