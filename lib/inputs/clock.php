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
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        global $APPLICATION;

        $APPLICATION->IncludeComponent("bitrix:main.clock","",Array(
            "INPUT_ID" => "",
            "INPUT_NAME" => $this->getValueName(),
            "INPUT_TITLE" => $this->label,
            "INIT_TIME" => $this->value,
            "STEP" => "5"
        ),
            ['HIDE_ICONS' => 'Y']
        );
    }
}