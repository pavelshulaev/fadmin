<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:40
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

/**
 * Class Number
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Number extends Text
{
    /**
     * @var bool
     */
    public static $cssPrinted = false;

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        if (!$this->input instanceof \Rover\Fadmin\Inputs\Number)
            return;

        if (!self::$cssPrinted){
            $this->printCss();
            self::$cssPrinted = true;
        }

        ?><input
            <?=$this->input->isDisabled() ? 'disabled="disabled"': '';?>
            type="number"
            id="<?=$this->input->getFieldId()?>"
            value="<?=$this->input->getValue()?>"
            name="<?=$this->input->getFieldName()?>"
            <?=is_numeric($this->input->getSize()) ? " size='{$this->input->getSize()}' " : ''?>
            <?=is_numeric($this->input->getMaxLength()) ? " maxlength='{$this->input->getMaxLength()}' " : ''?>
            <?=is_numeric($this->input->getMax()) ? " max='{$this->input->getMax()}' " : ''?>
            <?=is_numeric($this->input->getMin()) ? " min='{$this->input->getMin()}' " : ''?>
            <?=strlen($this->input->getPlaceholder()) ? " placeholder='{$this->input->getPlaceholder()}' " : ''?>
            ><?php
    }

    /**
     * @author Pavel Shulaev (http://rover-it.me)
     */
    protected function printCss()
    {
        ?>
        <style>
            .adm-workarea input[type="number"]{
                background: #fff;
                border: 1px solid;
                border-color: #87919c #959ea9 #9ea7b1 #959ea9;
                border-radius: 4px;
                color: #000;
                -webkit-box-shadow: 0 1px 0 0 rgba(255,255,255,0.3), inset 0 2px 2px -1px rgba(180,188,191,0.7);
                box-shadow: 0 1px 0 0 rgba(255,255,255,0.3), inset 0 2px 2px -1px rgba(180,188,191,0.7);
                display: inline-block;
                outline: none;
                vertical-align: middle;
                -webkit-font-smoothing: antialiased;
                font-size: 13px;
                height: 25px;
                padding: 0 5px;
                margin: 0;
            }
        </style>
        <?php
    }
}