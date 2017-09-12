<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:39
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin;

use Rover\Fadmin\Layout\Admin;

/**
 * Class Label
 *
 * @package Rover\Fadmin\Layout\Admin
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Label extends Admin
{
    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        echo $this->input->getLabel();
    }

}