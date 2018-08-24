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

use Rover\Fadmin\Layout\Admin\Input;

/**
 * Class Text
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 *
 * @param \Rover\Fadmin\Inputs\Text $input
 */
class Password extends Text
{
    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        if (!$this->input instanceof \Rover\Fadmin\Inputs\Password)
            return;

        ?><input
            type="password"
            <?=$this->input->isDisabled() ? 'disabled="disabled"': '';?>
            id="<?=$this->input->getFieldId()?>"
            size="<?=$this->input->getSize()?>"
            maxlength="<?=$this->input->getMaxLength()?>"
            value="<?=$this->input->getValue()?>"
            <?=strlen($this->input->getPlaceholder()) ? " placeholder='{$this->input->getPlaceholder()}' " : ''?>
            name="<?=$this->input->getFieldName()?>"><?php
    }
}