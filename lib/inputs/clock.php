<?php
namespace Rover\Fadmin\Inputs;
/**
 * Class Clock
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Clock extends Input
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__CLOCK;

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw()
	{
		global $APPLICATION;

		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId);

		$APPLICATION->IncludeComponent("bitrix:main.clock","",Array(
				"INPUT_ID" => "",
				"INPUT_NAME" => $valueName,
				"INPUT_TITLE" => $this->label,
				"INIT_TIME" => $this->value,
				"STEP" => "5"
			),
			['HIDE_ICONS' => 'Y']
		);

		$this->showHelp();
	}
}