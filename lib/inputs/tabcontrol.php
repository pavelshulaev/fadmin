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

use Bitrix\Main\NotImplementedException;
use Rover\Fadmin\Layout\Form;
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
    protected $tabsConfig = array();

    /** @var array */
    protected $presetMap = array();

    /**
     * Tabcontrol constructor.
     *
     * @param array   $tabsConfig
     * @param Options $options
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     */
    public function __construct(array $tabsConfig = array(), Options $options)
    {
        $params = array(
            'name'  => str_replace('.', '-', $options->getModuleId() . '_' . self::getType()),
            'label' => $options->getModuleId() . '_' . self::getType(),
        );

        parent::__construct($params, $options);

        $this->tabsConfig = $tabsConfig;
    }

    /**
     * @param bool $reload
     * @return Tab[]
     * @throws ArgumentNullException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getAdminTabs($reload = false)
    {
        $result = array();
        $tabs   = $this->getTabs($reload);
        $tabsCnt= count($tabs);

        for ($i = 0; $i < $tabsCnt; ++$i){
            /** @var Tab $tab */
            $tab = $tabs[$i];
            if (!$this->optionsEngine->settings->getShowAdminPresets()
                && $tab->isPreset())
                continue;

            $result[] = $tab;
        }

        return $result;
    }

    /**
     * @param bool $reload
     * @return Tab[]|null
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getTabs($reload = false)
    {
        return $this->getChildren($reload);
    }

    /**
     * @param bool $reload
     * @return mixed|null|Input[]
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getChildren($reload = false)
    {
        if (is_null($this->children) || $reload)
            $this->reloadTabs();

        return $this->optionsEngine->event
            ->handle(Options\Event::AFTER_GET_TABS, array('tabs' => $this->children))
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
        $this->children  = array();
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

                $presets = $this->optionsEngine->getPreset()->getList($siteId);

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

                        $tab = new Tab($resultTabParams, $this->optionsEngine, $this);

                        $this->children[] = $this->optionsEngine->event
                            ->handle(Options\Event::AFTER_MAKE_PRESET_TAB, compact('tab'))
                            ->getParameter('tab');
                    }
                }

            } else {
                $this->children[] = new Tab($tabParams, $this->optionsEngine, $this);
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

        foreach ($tabs as $tab) {

            /** @var Tab $tab */
            if ($tab->isPreset()
                && ($tab->getPresetId() == $presetId)
                && ($tab->getSiteId() == $siteId))
                return $tab;
        }

        return null;
    }

    /**
     * @param        $name
     * @param string $presetId
     * @param string $siteId
     * @param bool   $reload
     * @return null|Tab
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function searchTabByName($name, $siteId = '', $presetId = '', $reload = false)
    {
        $tabs       = $this->getTabs($reload);
        $siteId     = trim($siteId);
        $presetId   = trim($presetId);
        $tabsCnt    = count($tabs);

        for ($i = 0; $i < $tabsCnt; ++$i) {
            /** @var Tab $tab */
            $tab = $tabs[$i];
            if ($tab->getFieldName() != $name)
                continue;

            if (strlen($siteId) && ($siteId != $tab->getSiteId()))
                continue;

            if (strlen($presetId) && ($presetId != $tab->getPresetId()))
                continue;

            return $tab;
        }

        return null;
    }

    /**
     * @param bool $admin
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setValueFromRequest($admin = false)
    {
        if (!$this->optionsEngine->event
            ->handle(Options\Event::BEFORE_ADD_VALUES_FROM_REQUEST)
            ->isSuccess())
            return false;

        $tabs = $admin
            ? $this->getAdminTabs()
            : $this->getTabs();

        foreach ($tabs as $tab)
            /** @var Tab $tab */
            $tab->setValueFromRequest();

        // handle group rights tab
        if ($this->optionsEngine->settings->getGroupRights()) {
            ob_start();
            Form::includeGroupRightsTab();
            ob_clean();
        }

        if (!$this->optionsEngine->event
            ->handle(Options\Event::AFTER_ADD_VALUES_FROM_REQUEST, compact('tabs'))
            ->isSuccess())
            return false;

        return true;
    }

    /**
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function __clone()
    {
        $this->reloadTabs();
        parent::__clone();
    }

    /**
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function sort()
    {
        $tabs       = $this->getTabs();
        $tabsCnt    = count($tabs);

        for ($i = 0; $i < $tabsCnt; ++$i){
            /** @var Tab $tab */
            $tab = $tabs[$i];
            $tab->sort();
        }
    }

    /**
     * @param $siteId
     * @return $this|void
     * @throws NotImplementedException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setSiteId($siteId)
    {
        throw new NotImplementedException();
    }

    /**
     * @param $presetId
     * @return $this|void
     * @throws NotImplementedException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setPresetId($presetId)
    {
        throw new NotImplementedException();
    }

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    public function beforeSaveValue(&$value)
    {
        return false;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function beforeLoadValue()
    {
        return false;
    }
}