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
use \Rover\Fadmin\Options;
use \Rover\Fadmin\Tab;
use \Rover\Fadmin\Inputs\Input;

/**
 * Class TabMap
 *
 * @package Rover\Fadmin
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class TabMap
{
	/**
	 * tabs collection
	 * @var array
	 */
	protected $tabMap   = [];

	/**
	 * preset tabs collection
	 * @var array
	 */
	protected $presetMap = [];

	/**
	 * tabs params
	 * @var array
	 */
	protected $tabsParams = [];

	/**
	 * for events
	 * @var Options
	 */
	protected $options;

	/**
	 * @param Options $options
	 * @throws ArgumentNullException
	 */
	public function __construct(Options $options)
	{
		$this->options = $options;

		$config = $options->getConfigCache();

		if (is_array($config) && isset($config['tabs']))
			$this->tabsParams = $config['tabs'];
	}

	/**
	 * @param bool|false $reload
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getTabs($reload = false)
	{
		if (!count($this->tabMap) || $reload)
			$this->reloadTabs();

		$tabs = $this->tabMap;

		$this->options->runEvent(Options::EVENT__AFTER_GET_TABS, compact('tabs'));

		return $tabs;
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function reloadTabs()
	{
		$this->tabMap       = [];
		$this->presetMap    = [];

		foreach ($this->tabsParams as $tabParams){

			if (empty($tabParams))
				continue;

			if ($tabParams['preset']){
				$siteId = $tabParams['siteId'] ?: '';
				// preset tab can be only one on current site
				if (isset($this->presetMap[$siteId]))
					continue;

				$this->presetMap[$siteId] = true;

				$presets = $this->options->preset->getList($siteId);

				if (is_array($presets) && count($presets)){
					foreach ($presets as $preset){

						$tabParams['presetId']  = $preset['id'];
						$tabParams['label']     = $preset['name'];

						// event before create preset tab
						if (false === $this->options->runEvent(
								Options::EVENT__BEFORE_MAKE_PRESET_TAB,
								compact('tabParams')))
						return null;

						$tab = Tab::factory($tabParams, $this->options);

						// event after create preset tab
						$this->options->runEvent(
							Options::EVENT__AFTER_MAKE_PRESET_TAB,
							compact('tab'));

						$this->tabMap[] = $tab;
					}
				}
			} else {
				$this->tabMap[] = Tab::factory($tabParams, $this->options);
			}
		}
	}

	/**
	 * @param            $presetId
	 * @param string     $siteId
	 * @param bool|false $reload
	 * @return null|Tab
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getTabByPresetId($presetId, $siteId = '', $reload = false)
	{
		if (!$presetId)
			throw new ArgumentNullException('presetId');

		foreach ($this->getTabs($reload) as $tab)
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
	 * @param            $valueName
	 * @param bool|false $reload
	 * @return null|Input
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getInputByValueName($valueName, $reload = false)
	{
		$aTabs = $this->getTabs($reload);

		$filter = ['name' => $valueName];

		foreach ($aTabs as $tab){
			/**
			 * @var Tab $tab
			 */
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
     * @author Pavel Shulaev (http://rover-it.me)
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
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function searchInputByName($inputName, $presetId = '', $siteId = '', $reload = false)
    {
        $tabs = $this->getTabs($reload);

        foreach ($tabs as $tab) {
            /**
             * @var Tab $tab
             */
            if (($presetId && $tab->getPresetId() != $presetId)
                || ($siteId && $tab->getSiteId() != $siteId))
                continue;

            $input = $tab->searchByName($inputName);

            if ($input instanceof Input)
                return $input;
        }

        return null;
    }
}