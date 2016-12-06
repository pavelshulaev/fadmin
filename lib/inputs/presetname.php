<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 07.12.2016
 * Time: 1:06
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Rover\Fadmin\Presets;
use Rover\Fadmin\Tab;

class PresetName extends Text
{
	public static $type = self::TYPE__PRESET_NAME;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentOutOfRangeException
	 */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

		if (!$this->tab->isPreset())
			return;

		$presetId = $this->tab->getPresetId();
	//	var_dump($presetId);
		//die();
		if (!$presetId)
			return;

		$this->setValue($this->tab->getOptions()
			->getPresetNameById($presetId, $this->tab->getSiteId()));
	}

	/**
	 * @param $value
	 * @return bool
	 * @throws \Bitrix\Main\ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function beforeSaveRequest($value)
	{
		if (!$this->tab->isPreset())
			return true;

		$presetId = $this->tab->getPresetId();

		if (!$presetId)
			return true;

		Presets::updateName($presetId, $value,
			$this->tab->getModuleId(), $this->tab->getSiteId());

		return true;
	}
}