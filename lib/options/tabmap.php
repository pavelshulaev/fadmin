<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 21.01.2016
 * Time: 20:18
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Options;

use \Bitrix\Main\ArgumentNullException;;

use Bitrix\Main\ArgumentOutOfRangeException;
use Rover\Fadmin\Layout\Form;
use \Rover\Fadmin\Options;
use \Rover\Fadmin\Tab;
use \Rover\Fadmin\Inputs\Input;

/**
 * Class TabMap
 *
 * @package Rover\Fadmin
 * @author  Pavel Shulaev (http://rover-it.me)
 * @deprecated
 */
class TabMap
{
	/**
	 * tabs collection
	 * @var array
	 */
	protected $tabs = array();

	/**
	 * preset tabs collection
	 * @var array
	 */
	protected $presetMap = array();

	/**
	 * for events
	 * @var Options
	 */
	protected $options;

    /**
     * TabMap constructor.
     *
     * @param Options $options
     * @deprecated
     */
	public function __construct(Options $options)
	{
		$this->options = $options;
	}

    /**
     * @param bool $reload
     * @return array|mixed
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	protected function getTabsParams($reload = false)
    {
        $config = $this->options->getConfigCache($reload);

        return is_array($config) && isset($config['tabs'])
            ? $config['tabs']
            : array();
    }

    /**
     * @param bool $reload
     * @return array
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public function getTabs($reload = false)
	{
		if (!count($this->tabs) || $reload)
			$this->reloadTabs();

        return $this->options->event
            ->handle(Event::AFTER_GET_TABS, array('tabs' => $this->tabs))
            ->getParameter('tabs');
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
            if (!$this->options->settings->getShowAdminPresets()
                && $tab->isPreset())
                continue;

            $result[] = $tab;
        }

        return $result;
    }

    /**
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public function reloadTabs()
    {
        $this->tabs      = array();
        $this->presetMap = array();

        $tabsParams = $this->getTabsParams();

        foreach ($tabsParams as $tabParams) {

            if (empty($tabParams))
                continue;

            if (isset($tabParams['preset']) && $tabParams['preset']) {

                $siteId = $tabParams['siteId'] ?: '';
                // preset tab can be only one on current site
                if (isset($this->presetMap[$siteId]))
                    continue;

                $this->presetMap[$siteId] = true;

                $presets = $this->options->preset->getList($siteId);

                if (is_array($presets) && count($presets)) {
                    foreach ($presets as $preset) {

                        // event before create preset tab
                        if (!$this->options->event
                            ->handle(Event::BEFORE_MAKE_PRESET_TAB, array(
                                'tabParams' => $tabParams,
                                'presetId'  => $preset['id'],
                                'presetName'=> $preset['name']
                            ))->isSuccess())
                            continue;

                        $resultTabParams            = $this->options->event->getParameter('tabParams');
                        $resultTabParams['presetId']= $this->options->event->getParameter('presetId');
                        $resultTabParams['label']   = $this->options->event->getParameter('presetName');

                        $tab = Tab::factory($resultTabParams, $this->options);

                        $this->tabs[] = $this->options->event
                            ->handle(Event::AFTER_MAKE_PRESET_TAB, compact('tab'))
                            ->getParameter('tab');
                    }
                }

            } else {
                $this->tabs[] = Tab::factory($tabParams, $this->options);
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
            /**
             * @var Tab $tab
             */
            if ($tab->isPreset()
                && ($tab->getSiteId() == $siteId)
                && ($tab->getPresetId() == $presetId))
                return $tab;

		return null;
	}

    /**
     * @param      $valueName
     * @param bool $reload
     * @return null|Input
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public function getInputByValueName($valueName, $reload = false)
	{
		$aTabs = $this->getTabs($reload);

		$filter = array('name' => $valueName);

		foreach ($aTabs as $tab){
			/** @var Tab $tab */
			$input = $tab->search($filter);
			if ($input instanceof Input)
				return $input;
		}

		return null;
	}

    /**
     * @param        $name
     * @param string $siteId
     * @param bool   $reload
     * @return mixed|null|Tab
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function searchTabByName($name, $siteId = '', $reload = false)
	{
		$tabs   = $this->getTabs($reload);
        $siteId = trim($siteId);

		foreach ($tabs as $tab){
            /**
             * @var Tab $tab
             */
            if ($tab->getName() != $name)
			    continue;

            if (strlen($siteId) && ($siteId != $tab->getSiteId()))
                continue;

            return $tab;
        }

		return null;
	}

    /**
     * @param        $inputName
     * @param string $presetId
     * @param string $siteId
     * @param bool   $reload
     * @return null|Input
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function searchInputByName($inputName, $presetId = '', $siteId = '', $reload = false)
    {
        $tabs = $this->getTabs($reload);

        foreach ($tabs as $tab) {
            /** @var Tab $tab */
            if ((strlen($presetId) && ($tab->getPresetId() != $presetId))
                || (strlen($siteId) && ($tab->getSiteId() != $siteId)))
                continue;

            $input = $tab->searchByName($inputName);

            if ($input instanceof Input)
                return $input;
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
    public function setValuesFromRequest($admin = false)
    {
        if (!$this->options->event
            ->handle(Event::BEFORE_ADD_VALUES_FROM_REQUEST)
            ->isSuccess())
            return false;

        $tabs = $admin
            ? $this->getAdminTabs()
            : $this->getTabs();

        foreach ($tabs as $tab)
            $tab->setValuesFromRequest();

        // handle group rights tab
        if ($this->options->settings->getGroupRights()) {
            ob_start();
            Form::includeGroupRightsTab();
            ob_clean();
        }

        if (!$this->options->event
            ->handle(Event::AFTER_ADD_VALUES_FROM_REQUEST, compact('tabs'))
            ->isSuccess())
            return false;

        return true;
    }

    /**
     * @param        $value
     * @param string $siteId
     * @return bool|int|mixed
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function addPreset($value, $siteId = '')
    {
        if (!$this->options->event
            ->handle(Event::BEFORE_ADD_PRESET, compact('siteId', 'value'))
            ->isSuccess())
            return false;

        $params = $this->options->event->getParameters();

        if (!isset($params['name']))
            $params['name'] = $params['value'];

        $params['id'] = $this->options->preset->add(
            $params['name'],
            $params['siteId']
        );

        $params = $this->options->event
            ->handle(Event::AFTER_ADD_PRESET, $params)
            ->getParameters();

        // reload tabs after event!!!
        $this->reloadTabs();

        return $params['id'];
    }

    /**
     * @param        $id
     * @param string $siteId
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function removePreset($id, $siteId = '')
    {
        $id = intval($id);
        if (!$id)
            throw new ArgumentNullException('id');

        // action beforeRemovePreset
        if (!$this->options->event
            ->handle(Event::BEFORE_REMOVE_PRESET, compact('siteId', 'id'))
            ->isSuccess())
            return false;

        $params     = $this->options->event->getParameters();

        /** @var Tab $presetTab */
        $presetTab  = $this->getTabByPresetId($params['id'], $params['siteId']);

        if ($presetTab instanceof Tab === false)
            throw new ArgumentOutOfRangeException('tab');

        $presetTab->clear();

        $this->options->preset->remove($id, $siteId);

        // action afterRemovePreset
        $this->options->event->handle(Event::AFTER_REMOVE_PRESET, $params);

        return true;
    }
}