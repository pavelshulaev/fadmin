<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 14:08
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */
namespace Rover\Fadmin\Layout\Admin;

use Rover\Fadmin\Layout\Input as InputAbstract;
/**
 * Class Admin
 *
 * @package Rover\Fadmin\Layout
 * @author  Pavel Shulaev (https://rover-it.me)
 */
abstract class Input extends InputAbstract
{
    /**
     * for factory
     * @var string
     */
    public static $type = 'Admin';

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw()
    {
        $this->showRowStart();
        $this->showCells();
        $this->showRowEnd();
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showCells()
    {
        $this->showLabelCell('width="50%" class="adm-detail-content-cell-l" style="vertical-align: top; padding-top: 7px;"');
        $this->showInputCell('width="50%" class="adm-detail-content-cell-r"');
    }

    /**
     * @param      $cellParams
     * @param bool $empty
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showLabelCell($cellParams, $empty = false)
    {
        $this->showCellStart($cellParams);
        $this->showLabel($empty);
        $this->showCellEnd();
    }

    /**
     * @param $cellParams
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInputCell($cellParams)
    {
        $this->showCellStart($cellParams);
        $this->showPreInput();
        $this->showInput();
        $this->showPostInput();
        $this->showHelp();
        $this->showCellEnd();
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getCommonAttributes()
    {
        return
            ' id="' . $this->input->getFieldId() . '" '.
            ' type="' . $this->getType() . '" '.
            static::getValue() .
            ' name="' . $this->input->getFieldName() . '" ' .
            ($this->input->isRequired() ? ' required="required" ': '') .
            ($this->input->isDisabled() ? ' disabled="disabled" ': '');
    }

    /**
     * @return string
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getValue()
    {
        return ' value="' . $this->input->getValue() . '" ';
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showRowStart()
    {
        echo '<tr>';
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showRowEnd()
    {
        echo '</tr>';
    }

    /**
     * @param $params
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showCellStart($params)
    {
        ?><td <?=trim($params)?>><?
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showCellEnd()
    {
        echo '</td>';
    }

    /**
     * @param bool $empty
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showLabel($empty = false)
    {
       if ($empty) return;

       ?><label for="<?=$this->input->getFieldId()?>"><?=$this->input->getLabel()?>:</label><?php
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function showMultiLabel()
    {
        ?><label for="<?=$this->input->getFieldId()?>"><?=$this->input->getLabel()?>:<br>
            <img src="/bitrix/images/main/mouse.gif" width="44" height="21" border="0" alt="">
        </label><?php
    }

    /**
     * @param bool $br
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function showHelp($br = true)
    {
        $help = trim($this->input->getHelp());

        if (strlen($help)):
            if ($br) echo '<br>';
            ?><small style="color: #777;"><?=$help?></small><?php
        endif;
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showPreInput()
    {
        $preInput = $this->input->getPreInput();
        if (strlen($preInput)) echo $preInput;
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showPostInput()
    {
        $postInput = $this->input->getPostInput();
        if (strlen($postInput)) echo $postInput;
    }
}