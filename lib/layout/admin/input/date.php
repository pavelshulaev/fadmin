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

/**
 * Class Date
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Date extends DateTime
{
    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        $this->hideTime();
        parent::showInput();
    }
}