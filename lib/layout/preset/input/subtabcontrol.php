<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 14.06.2018
 * Time: 7:47
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Preset\Input;

use Rover\Fadmin\Layout\Preset\Input;

/**
 * Class SubTabControl
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class SubTabControl extends Input
{
    /**
     * @return mixed|void
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw()
    {
        $this->showInput();
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        $this->adminInput->showInput();
    }
}