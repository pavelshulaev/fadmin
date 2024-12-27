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

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
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
     */
    public static string $type = 'Admin';

    /**
     * @return void
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw(): void
    {
        $this->showRowStart();
        $this->showCells();
        $this->showRowEnd();
    }

    /**
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showCells(): void
    {
        $this->showLabelCell('width="50%" class="adm-detail-content-cell-l" style="vertical-align: top; padding-top: 7px;"');
        $this->showInputCell('width="50%" class="adm-detail-content-cell-r"');
    }

    /**
     * @param string $cellParams
     * @param bool $empty
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showLabelCell(string $cellParams, bool $empty = false): void
    {
        $this->showCellStart($cellParams);
        $this->showLabel($empty);
        $this->showCellEnd();
    }

    /**
     * @param $cellParams
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInputCell($cellParams): void
    {
        $this->showCellStart($cellParams);
        $this->showPreInput();
        $this->showInput();
        $this->showPostInput();
        $this->showHelp();
        $this->showCellEnd();
    }

    /**
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getCommonAttributes(): string
    {
        return
            ' id="' . $this->input->getFieldId() . '" ' .
            ' type="' . $this->getType() . '" ' .
            static::getValue() .
            ' name="' . $this->input->getFieldName() . '" ' .
            ($this->input->isRequired() ? ' required="required" ' : '') .
            ($this->input->isDisabled() ? ' disabled="disabled" ' : '');
    }

    /**
     * @return string
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getValue(): string
    {
        return ' value="' . $this->input->getValue() . '" ';
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showRowStart(): void
    {
        echo '<tr>';
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showRowEnd(): void
    {
        echo '</tr>';
    }

    /**
     * @param $params
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showCellStart($params): void
    {
        ?><td <?= trim($params) ?>><?
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showCellEnd(): void
    {
        echo '</td>';
    }

    /**
     * @param bool $empty
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showLabel(bool $empty = false): void
    {
        if ($empty) {
            return;
        }

        ?><label for="<?= $this->input->getFieldId() ?>"><?= $this->input->getLabel() ?>:</label><?php
    }

    /**
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function showMultiLabel(): void
    {
        ?><label for="<?= $this->input->getFieldId() ?>"><?= $this->input->getLabel() ?>:<br>
        <img src="/bitrix/images/main/mouse.gif" width="44" height="21" border="0" alt="">
        </label><?php
    }

    /**
     * @param bool $addBr
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function showHelp(bool $addBr = true): void
    {
        $help = trim($this->input->getHelp());

        if (strlen($help)):
            if ($addBr) {
                echo '<br>';
            }
            ?><small style="color: #777;"><?= $help ?></small><?php
        endif;
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showPreInput(): void
    {
        $preInput = $this->input->getPreInput();
        if (strlen($preInput)) {
            echo $preInput;
        }
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showPostInput(): void
    {
        $postInput = $this->input->getPostInput();
        if (strlen($postInput)) {
            echo $postInput;
        }
    }
}