<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 14:41
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Bitrix\Main\ArgumentNullException;
use Rover\Fadmin\Layout\Admin\Input;

/**
 * Class Submit
 *
 * @package Rover\Fadmin\Layout\Preset\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Submit extends Input
{
    protected string $customInputName;
    protected string $customInputValue;
    protected string $customInputId;
    protected string $customPopup;

    /**
     * @param $valueId
     * @param $popup
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function confirm($valueId, $popup): void
    {
        if ($popup === false) {
            return;
        }
        ?>
        <script>
            (function () {
                document.getElementById('<?=$valueId?>').onclick = function () {
                    return confirm('<?=$popup?>');
                }
            })();
        </script>
        <?php
    }

    /**
     * @return void
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput(): void
    {
        if (!$this->input instanceof \Rover\Fadmin\Inputs\Submit) {
            return;
        }

        $name  = $this->customInputName ?? $this->input->getFieldName();
        $value = $this->customInputValue ?? $this->input->getDefault();
        $id    = $this->customInputId ?? $this->input->getFieldId();
        $popup = $this->customPopup ?? $this->input->getPopup();

        ?>
        <style>
            button[name="<?=$name?>"] {
                -webkit-border-radius: 4px;
                border-radius: 4px;
                border: none;
                /* border-top: 1px solid #fff; */
                -webkit-box-shadow: 0 0 1px rgba(0, 0, 0, .11), 0 1px 1px rgba(0, 0, 0, .3), inset 0 1px #fff, inset 0 0 1px rgba(255, 255, 255, .5);
                box-shadow: 0 0 1px rgba(0, 0, 0, .3), 0 1px 1px rgba(0, 0, 0, .3), inset 0 1px 0 #fff, inset 0 0 1px rgba(255, 255, 255, .5);
                background-color: #e0e9ec;
                background-image: -webkit-linear-gradient(bottom, #d7e3e7, #fff) !important;
                background-image: -moz-linear-gradient(bottom, #d7e3e7, #fff) !important;
                background-image: -ms-linear-gradient(bottom, #d7e3e7, #fff) !important;
                background-image: -o-linear-gradient(bottom, #d7e3e7, #fff) !important;
                background-image: linear-gradient(bottom, #d7e3e7, #fff) !important;
                color: #3f4b54;
                cursor: pointer;
                display: inline-block;
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                font-weight: bold;
                font-size: 13px;
                /* line-height: 18px; */
                height: 29px;
                text-shadow: 0 1px rgba(255, 255, 255, 0.7);
                text-decoration: none;
                position: relative;
                vertical-align: middle;
                -webkit-font-smoothing: antialiased;
                padding: 1px 13px 3px;
            }

            button[name=<?=$name?>]:hover {
                text-decoration: none;
                background: #f3f6f7 !important;
                background-image: -webkit-linear-gradient(top, #f8f8f9, #f2f6f8) !important;
                background-image: -moz-linear-gradient(top, #f8f8f9, #f2f6f8) !important;
                background-image: -ms-linear-gradient(top, #f8f8f9, #f2f6f8) !important;
                background-image: -o-linear-gradient(top, #f8f8f9, #f2f6f8) !important;
                background-image: linear-gradient(top, #f8f8f9, #f2f6f8) !important;
            }
        </style>

        <button type='submit'
        <?= $this->input->isDisabled() ? 'disabled="disabled"' : ''; ?>
                id="<?= $this->input->getFieldId() ?>"
                name="<?= $name ?>"
                value="<?= urlencode($value) ?>"><?= $this->input->getLabel() ?></button><?php

        $this->confirm($id, $popup);
    }

    /**
     * @return mixed
     */
    public function getCustomInputName(): string
    {
        return $this->customInputName;
    }

    /**
     * @param $customInputName
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setCustomInputName($customInputName): static
    {
        $this->customInputName = $customInputName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomInputValueId(): string
    {
        return $this->customInputValue;
    }

    /**
     * @param $customInputValue
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setCustomInputValueId($customInputValue): static
    {
        $this->customInputValue = $customInputValue;

        return $this;
    }
}