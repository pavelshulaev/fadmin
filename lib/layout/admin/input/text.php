<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 14:31
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Rover\Fadmin\Layout\Admin\Input;

/**
 * Class Text
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 *
 * @param \Rover\Fadmin\Inputs\Text $input
 */
class Text extends Input
{
    /**
     * @return void
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput(): void
    {
        if (!$this->input instanceof \Rover\Fadmin\Inputs\Text)
            return;

        ?><input
            <?=$this->getCommonAttributes()?>
            size="<?=$this->input->getSize()?>"
            maxlength="<?=$this->input->getMaxLength()?>"
            <?=strlen($this->input->getPlaceholder()) ? " placeholder='{$this->input->getPlaceholder()}' " : ''?>
            ><?php
    }
}