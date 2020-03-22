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
use Rover\Fadmin\Options;

Loc::loadMessages(__FILE__);

/**
 * Class PresetName
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class PresetName extends Text
{
    /**
     * PresetName constructor.
     *
     * @param array      $params
     * @param Options    $options
     * @param Input|null $parent
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     */
	public function __construct(array $params, Options $options, Input $parent = null)
	{
		parent::__construct($params, $options, $parent);

		if (!$this->isPreset())
			return;

		$presetId = $this->getPresetId();

		if (!$presetId)
			return;

		$value = $this->getValue();

		if (empty($value) || ($value == $this->getDefault()))
			$this->setValue($this->optionsEngine
				->getPreset()->getNameById($presetId, $this->getSiteId()));
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
		if (!$this->isPreset())
			return true;

		$presetId = $this->getPresetId();

		if (!$presetId)
			return true;

		if (empty($value)){
			$this->optionsEngine->message->addError(
				Loc::getMessage('rover-fa__presetname-no-name',
                    array('#last-preset-name#' => $this->getValue())));

			return false;
		}

		$this->optionsEngine->getPreset()->updateName($presetId, $value,
			$this->getSiteId());

		return true;
	}
}