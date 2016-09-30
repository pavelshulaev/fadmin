<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 21.01.2016
 * Time: 20:18
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin;

use \Bitrix\Main\ArgumentNullException;;

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
	 * @param Options $options
	 */
	public function __construct(Options $options)
	{
		$this->options = $options; // for events
	}

	/**
	 * adding tab to map
	 * @param Tab $tab
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function addTab(Tab $tab)
	{
		if ($tab->isPreset()) {

			// preset tab can be only one on current site
			if (isset($this->presetMap[$tab->getSiteId()]))
				return;

			$this->presetMap[$tab->getSiteId()] = true;
		}

		$this->tabMap[] = $tab;
	}

	/**
	 * @param        $presetId
	 * @param string $siteId
	 * @return null
	 * @throws \Bitrix\Main\ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getTabByPresetId($presetId, $siteId = '')
	{
		if (!$presetId)
			throw new ArgumentNullException('presetId');

		foreach ($this->tabMap as $tab)
			/**
			 * @var Tab $tab
			 */
			if ($tab->isPreset() && $tab->getSiteId() == $siteId)
				return $this->createPresetTab($tab, $presetId);

		return null;
	}

	/**
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getAllTabsInfo()
	{
		$tabs           = $this->getTabs();
		$allTabsInfo    = [];

		foreach ($tabs as $tab)
			/**
			 * @var Tab $tab
			 */
			$allTabsInfo[] = $tab->getInfo();

		return $allTabsInfo;
	}

	/**
	 * @param Tab $tab
	 * @param     $presetId
	 * @return Tab
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function createPresetTab(Tab $tab, $presetId)
	{
		if (!$presetId)
			throw new ArgumentNullException('presetId');

		// event before create preset tab
		if (false === $this->options->runEvent(
			Options::EVENT__BEFORE_MAKE_PRESET_TAB,
			compact('tab', 'presetId')))
			return null;

		$newTab = clone $tab;
		$newTab->setPresetId($presetId);

		// event after create preset tab
		$this->options->runEvent(
			Options::EVENT__AFTER_MAKE_PRESET_TAB,
			compact('newTab'));

		return $newTab;
	}

	/**
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getTabs()
	{
		$response = array();

		foreach ($this->tabMap as $tab){
			/**
			 * @var Tab $tab
			 */
			if ($tab->isPreset()) {

				$presets = Presets::get($this->options->getModuleId(), $tab->getSiteId());

				if (is_array($presets) && count($presets)){
					foreach ($presets as $preset){
						$presetTab = $this->createPresetTab($tab, $preset['id']);
						$presetTab->setLabel($preset['name']);

						$response[] = $presetTab;
					}
				}

			} else {
				$response[] = $tab;
			}
		}

		return $response;
	}

	/**
	 * @param $valueName
	 * @return null
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getInputByValueName($valueName)
	{
		$aTabs = $this->getTabs();

		$filter = ['name' => $valueName];

		foreach ($aTabs as $tab){
			/**
			 * @var Tab $tab
			 */
			$input = $tab->search($filter);
			if ($input instanceof Inputs\Input)
				return $input;
		}

		return null;
	}
}