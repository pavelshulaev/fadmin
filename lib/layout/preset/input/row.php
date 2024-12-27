<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:07
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Preset\Input;
/**
 * Class Addpreset
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Row extends Submit
{
    /**
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function draw(): void
    {
        $this->adminInput->draw();
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput(): void
    {
        $this->adminInput->showInput();
    }
}