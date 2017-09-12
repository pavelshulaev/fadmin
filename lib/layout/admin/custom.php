<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 14:21
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin;

use Rover\Fadmin\Layout\Admin;

class Custom extends Admin
{
    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw()
    {
        ?><tr>
            <td colspan="2"><?=$this->input->getLabel()?></td>
        </tr><?php
    }

    /**
     * for capability
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput(){}
}