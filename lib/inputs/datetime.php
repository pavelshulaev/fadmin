<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:50
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;
/**
 * Class Clock
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class DateTime extends Input
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__DATE;


	/**
	 * @param bool|true $time
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw($time = true)
	{
		global $APPLICATION;

		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId);

		$APPLICATION->IncludeComponent("bitrix:main.calendar","",Array(
				"SHOW_INPUT" => "Y",
				"FORM_NAME" => "",
				"INPUT_NAME" => $valueName,
				"INPUT_NAME_FINISH" => "",
				"INPUT_VALUE" => $this->value,
				"INPUT_VALUE_FINISH" => '',
				"SHOW_TIME" => $time ? 'Y' : "N",
				"HIDE_TIMEBAR" => $time ? 'N' : "Y"
			)
		);

		$this->showHelp();
	}

	/**
	 * @param $value
	 * @return string
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function beforeSaveRequest($value)
	{
		if ($this->multiple)
			$value = serialize($value);

		return $value;
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function afterLoadValue()
	{
		if ($this->multiple) {
			$this->value = unserialize($this->value);
			if (!$this->value)
				$this->value = [];
		} elseif (!$this->value) {
			$this->value = 0;
		}
	}
}