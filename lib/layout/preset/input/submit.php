<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 14:41
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Preset\Input;

use Rover\Fadmin\Layout\Preset\Input;

/**
 * Class Submit
 *
 * @package Rover\Fadmin\Layout\Preset\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Submit extends Input
{
    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput(): void
    {
        $this->adminInput->showInput();
    }
}