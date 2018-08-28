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
        $this->showLabel();
        $this->showInput();
        $this->showHelp();
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
            ' value="' . $this->input->getValue() . '" ' .
            ' name="' . $this->input->getFieldName() . '" ' .
            ($this->input->isRequired() ? ' required="required" ': '') .
            ($this->input->isDisabled() ? ' disabled="disabled" ': '');
    }

    /**
     * @param bool $empty
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showLabel($empty = false)
    {
        ?>
        <tr>
        <td
            width="50%"
            class="adm-detail-content-cell-l"
            style="vertical-align: top; padding-top: 7px;">
            <?php if (!$empty) : ?>
                <label for="<?=$this->input->getValueId()?>"><?=$this->input->getLabel()?>:</label>
            <?php endif; ?>
        </td>
        <td
            width="50%"
            class="adm-detail-content-cell-r"
        ><?php
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function showMultiLabel()
    {
        ?>
        <tr>
        <td
            width="50%"
            class="adm-detail-content-cell-l"
            style="vertical-align: top; padding-top: 7px;">
            <label for="<?=$this->input->getValueId()?>"><?=$this->input->getLabel()?>:<br>
                <img src="/bitrix/images/main/mouse.gif" width="44" height="21" border="0" alt="">
            </label>
        </td>
        <td
            width="50%"
            class="adm-detail-content-cell-r"
        ><?php
    }

    /**
     * @author Pavel Shulaev (http://rover-it.me)
     */
    protected function showHelp()
    {
        $help = trim($this->input->getHelp());

        if (strlen($help)):
            ?><br><small style="color: #777;"><?=$help?></small><?php
        endif;

        ?></td>
        </tr>
        <?php
    }
}