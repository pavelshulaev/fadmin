<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:43
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Preset\Input;

use Rover\Fadmin\Layout\Preset\Input;

/**
 * Class Radio
 *
 * @package Rover\Fadmin\Layout\Preset\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Radio extends Input
{
    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput(): void
    {
        $this->adminInput->showInput();
    }
}