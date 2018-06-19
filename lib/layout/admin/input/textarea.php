<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:14
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Rover\Fadmin\Layout\Admin\Input;

/**
 * Class Textarea
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Textarea extends Input
{
    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        if (!$this->input instanceof \Rover\Fadmin\Inputs\Textarea)
            return;

        ?><textarea
        <?=$this->input->isDisabled() ? 'disabled="disabled"': '';?>
        id="<?=$this->input->getValueId()?>"
        rows="<?=$this->input->getRows()?>"
        cols="<?=$this->input->getCols()?>"
        name="<?=$this->input->getValueName()?>"><?=$this->input->getValue()?></textarea><?php
    }

    /**
     * @param bool $empty
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showLabel($empty = false)
    {
        $valueId = $this->input->getValueId();
        ?>
        <tr>
        <td
            width="50%"
            style="vertical-align: top; padding-top: 7px;"
            class="adm-detail-valign-top">
            <?php if (!$empty) : ?>
                <label for="<?=$valueId?>"><?=$this->input->getLabel()?>:</label>
            <?php endif; ?>
        </td>
        <td width="50%"><?php
    }

}