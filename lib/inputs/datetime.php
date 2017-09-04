<?php
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
     * show time flag
     * @var bool
     */
	protected $showTime = true;

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function hideTime()
    {
        $this->showTime = false;
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function showTime()
    {
        $this->showTime = true;
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function showInput()
    {
        global $APPLICATION;

        $APPLICATION->IncludeComponent("bitrix:main.calendar","",Array(
                "SHOW_INPUT" => "Y",
                "FORM_NAME" => "",
                "INPUT_NAME" => $this->getValueName(),
                "INPUT_NAME_FINISH" => "",
                "INPUT_VALUE" => $this->value,
                "INPUT_VALUE_FINISH" => '',
                "SHOW_TIME"     => $this->showTime ? 'Y' : "N",
                "HIDE_TIMEBAR"  => $this->showTime ? 'N' : "Y"
            )
        );
    }
}