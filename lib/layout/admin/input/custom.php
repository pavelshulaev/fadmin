<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 14:21
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Rover\Fadmin\Layout\Admin\Input;

/**
 * Class Custom
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Custom extends Input
{
    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw(): void
    {
        $this->showRowStart();
        ?><td colspan="2"><?=$this->input->getLabel()?></td><?php
        $this->showRowEnd();
    }

    /**
     * for capability
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput(): void
    {}
}