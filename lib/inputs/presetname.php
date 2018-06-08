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

use Bitrix\Main\Localization\Loc;
use Rover\Fadmin\Tab;

Loc::loadMessages(__FILE__);

/**
 * Class PresetName
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class PresetName extends Text
{
	public static $type = self::TYPE__PRESET_NAME;

    /**
     * PresetName constructor.
     *
     * @param array $params
     * @param Tab   $tab
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

		if (!$this->tab->isPreset())
			return;

		$presetId = $this->tab->getPresetId();

		if (!$presetId)
			return;

		$value = $this->getValue();
		if (empty($value))
			$this->setValue($this->tab->options
				->preset->getNameById($presetId, $this->tab->getSiteId()));
	}

    /**
     * @param $value
     * @return bool|mixed
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
	public function beforeSaveRequest(&$value)
	{
		if (!$this->tab->isPreset())
			return true;

		$presetId = $this->tab->getPresetId();

		if (!$presetId)
			return true;

		if (empty($value)){
			$this->tab->options->message->addError(
				Loc::getMessage('rover-fa__presetname-no-name',
                    array('#last-preset-name#' => $this->getValue())));

			return false;
		}

		$this->tab->options->preset->updateName($presetId, $value,
			$this->tab->getSiteId());

		return true;
	}
}