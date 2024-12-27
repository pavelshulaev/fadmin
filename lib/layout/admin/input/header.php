<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 14:54
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Rover\Fadmin\Layout\Admin\Input;

/**
 * Class Header
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Header extends Input
{
    /**
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function draw(): void
    {
        ?><tr class="heading">
            <td colspan="2"><?=$this->input->getLabel()?></td>
        </tr><?php
    }

    /**
     * for capability
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput(): void
    {}

}