<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:28
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Rover\Fadmin\Layout\Admin\Input;

/**
 * Class DateTime
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class DateTime extends Input
{
    /**
     * show time flag
     * @var bool
     */
    protected bool $showTime = true;

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function hideTime(): void
    {
        $this->showTime = false;
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showTime(): void
    {
        $this->showTime = true;
    }

    /**
     * @return void
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput(): void
    {
        global $APPLICATION;

        $APPLICATION->IncludeComponent("bitrix:main.calendar", "", array(
            "SHOW_INPUT"    => "Y",
            "FORM_NAME"     => "",
            "INPUT_NAME"    => $this->input->getFieldName(),
            "INPUT_NAME_FINISH" => "",
            "INPUT_VALUE"   => $this->input->getValue(),
            "INPUT_VALUE_FINISH" => '',
            "SHOW_TIME"     => $this->showTime ? 'Y' : "N",
            "HIDE_TIMEBAR"  => $this->showTime ? 'N' : "Y"
        ));
    }
}