<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:43
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Rover\Fadmin\Layout\Admin\Input;

/**
 * Class Radio
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Radio extends Input
{
    /**
     * @return mixed|void
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        if (!$this->input instanceof \Rover\Fadmin\Inputs\Radio)
            return;

        $value = $this->input->getValue();

        foreach ($this->input->getOptions() as $optionValue => $optionName):

            ?><label><input
                id="<?=$this->input->getFieldId()?>_<?=$optionValue?>"
                type="<?=$this->getType()?>"
                value="<?=$optionValue?>"
                name="<?=$this->input->getFieldName()?>"
                <?=$value == $optionValue ? ' checked="checked" ' : ''
                ?>> <?=$optionName?></label><br><?php

        endforeach;
    }
}