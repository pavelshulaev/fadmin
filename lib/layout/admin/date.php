<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:28
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin;

class Date extends DateTime
{
    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        $this->hideTime();
        parent::showInput();
    }
}