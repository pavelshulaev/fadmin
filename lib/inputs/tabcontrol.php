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
    protected array $tabsConfig;
    protected array $presetMap;

    /**
     * Tabcontrol constructor.
     *
     * @param array $tabsConfig
     * @param Options $options
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     */
    public function __construct(array $tabsConfig, Options $options)
    {
        $params = [
            'name'  => str_replace('.', '-', $options->getModuleId() . '_' . self::getType()),
            'label' => $options->getModuleId() . '_' . self::getType(),
        ];

        parent::__construct($params, $options);

        $this->tabsConfig = $tabsConfig;
    }

    /**
     * @param bool $reload
     * @return Tab[]
     * @throws ArgumentNullException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getAdminTabs(bool $reload = false): array
    {
        $result  = [];
        $tabs    = $this->getTabs($reload);
        $tabsCnt = count($tabs);

        for ($i = 0; $i < $tabsCnt; ++$i) {
            /** @var Tab $tab */
            $tab = $tabs[$i];
            if (!$this->optionsEngine->settings->getShowAdminPresets()
                && $tab->isPreset()) {
                continue;
            }

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
    public function getTabs(bool $reload = false): ?array
    {
        return $this->getChildren($reload);
    }

    /**
     * @param bool $reload
     * @return array
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getChildren(bool $reload = false): array
    {
        if (!isset($this->children) || $reload) {
            $this->reloadTabs();
        }

        return $this->optionsEngine->event
            ->handle(Options\Event::AFTER_GET_TABS, ['tabs' => $this->children])
            ->getParameter('tabs');
    }

    /**
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function reloadTabs(): void
    {
        $this->children  = [];
        $this->presetMap = [];

        foreach ($this->tabsConfig as $tabParams) {

            if (empty($tabParams)) {
                continue;
            }

            if (isset($tabParams['preset']) && $tabParams['preset']) {

                $siteId = $tabParams['siteId'] ?: '';
                // preset tab can be only one on current site
                if (isset($this->presetMap[$siteId])) {
                    continue;
                }

                $this->presetMap[$siteId] = true;

                $presets = $this->optionsEngine->getPreset()->getList($siteId);

                if (is_array($presets) && count($presets)) {
                    foreach ($presets as $preset) {

                        // event before create preset tab
                        if (!$this->optionsEngine->event
                            ->handle(Options\Event::BEFORE_MAKE_PRESET_TAB, [
                                'tabParams'  => $tabParams,
                                'presetId'   => $preset['id'],
                                'presetName' => $preset['name']
                            ])->isSuccess()) {
                            continue;
                        }

                        $resultTabParams             = $this->optionsEngine->event->getParameter('tabParams');
                        $resultTabParams['presetId'] = $this->optionsEngine->event->getParameter('presetId');
                        $resultTabParams['label']    = $this->optionsEngine->event->getParameter('presetName');

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
     * @param string $presetId
     * @param string $siteId
     * @param bool $reload
     * @return Input|Tab|null
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getTabByPresetId(string $presetId, string $siteId = '', bool $reload = false)
    {
        $presetId = intval($presetId);
        if (!$presetId) {
            throw new ArgumentNullException('presetId');
        }

        $tabs = $this->getTabs($reload);

        foreach ($tabs as $tab) {

            /** @var Tab $tab */
            if ($tab->isPreset()
                && ($tab->getPresetId() == $presetId)
                && ($tab->getSiteId() == $siteId)) {
                return $tab;
            }
        }

        return null;
    }

    /**
     * @param string $name
     * @param string $siteId
     * @param string $presetId
     * @param bool $reload
     * @return null|Tab
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function searchTabByName(string $name, string $siteId = '', string $presetId = '',
        bool $reload = false): ?Tab
    {
        $tabs     = $this->getTabs($reload);
        $siteId   = trim($siteId);
        $presetId = trim($presetId);
        $tabsCnt  = count($tabs);

        for ($i = 0; $i < $tabsCnt; ++$i) {
            /** @var Tab $tab */
            $tab = $tabs[$i];
            if ($tab->getFieldName() != $name) {
                continue;
            }

            if (strlen($siteId) && ($siteId != $tab->getSiteId())) {
                continue;
            }

            if (strlen($presetId) && ($presetId != $tab->getPresetId())) {
                continue;
            }

            return $tab;
        }

        return null;
    }

    /**
     * @param bool $admin
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setValueFromRequest(bool $admin = false): bool
    {
        if (!$this->optionsEngine->event
            ->handle(Options\Event::BEFORE_ADD_VALUES_FROM_REQUEST)
            ->isSuccess()) {
            return false;
        }

        $tabs = $admin
            ? $this->getAdminTabs()
            : $this->getTabs();

        foreach ($tabs as $tab) /** @var Tab $tab */ {
            $tab->setValueFromRequest();
        }

        // handle group rights tab
        if ($this->optionsEngine->settings->getGroupRights()) {
            ob_start();
            Form::includeGroupRightsTab();
            ob_clean();
        }

        if (!$this->optionsEngine->event
            ->handle(Options\Event::AFTER_ADD_VALUES_FROM_REQUEST, compact('tabs'))
            ->isSuccess()) {
            return false;
        }

        return true;
    }

    /**
     * @throws SystemException
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
    public function sort(): void
    {
        $tabs    = $this->getTabs();
        $tabsCnt = count($tabs);

        for ($i = 0; $i < $tabsCnt; ++$i) {
            /** @var Tab $tab */
            $tab = $tabs[$i];
            $tab->sort();
        }
    }

    /**
     * @param $siteId
     * @return Tabcontrol
     * @throws NotImplementedException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setSiteId($siteId): static
    {
        throw new NotImplementedException();
    }

    /**
     * @param $presetId
     * @return $this|void
     * @throws NotImplementedException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setPresetId($presetId): static
    {
        throw new NotImplementedException();
    }

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    public function beforeSaveValue(&$value): bool
    {
        return false;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function beforeLoadValue(): bool
    {
        return false;
    }
}