<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 13.08.2017
 * Time: 19:25
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */
namespace Rover\Fadmin\Helper;

use \Rover\Fadmin\Inputs\Input;
/**
 * Class Layout
 *
 * @package Rover\Fadmin\Helper
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Layout
{
    /**
     * @param Input $input
     * @param bool  $empty
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function label(Input $input, $empty = false)
    {
        ?>
        <tr>
            <td
                width="50%"
                class="adm-detail-content-cell-l"
                style="vertical-align: top; padding-top: 7px;">
                <?php if (!$empty) : ?>
                    <label for="<?=$input->getValueId()?>"><?=$input->getLabel()?>:</label>
                <?php endif; ?>
            </td>
            <td
            width="50%"
            class="adm-detail-content-cell-r"
            ><?php
    }

    /**
     * @param Input $input
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function help(Input $input)
    {
        $help = trim($input->getHelp());

        if (strlen($help)):
            ?><br><small style="color: #777;"><?=$help?></small><?php
        endif;

        ?></td>
        </tr>
    <?php
    }

    /**
     * @param Input $input
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function multiLabel(Input $input)
    {
        ?>
        <tr>
            <td
                width="50%"
                class="adm-detail-content-cell-l"
                style="vertical-align: top; padding-top: 7px;">
                <label for="<?=$input->getValueId()?>"><?=$input->getLabel()?>:<br>
                    <img src="/bitrix/images/main/mouse.gif" width="44" height="21" border="0" alt="">
                </label>
            </td>
            <td
                width="50%"
                class="adm-detail-content-cell-r"
            ><?php
    }

    /**
     * @param Input $input
     * @param null  $name
     * @param null  $value
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function submit(Input $input, $name = null, $value = null)
    {
        if (is_null($name))
            $name = $input->getValueName();

        if (is_null($value))
            $value = $input->getDefault();

        ?>
        <style>
            button[name="<?=$name?>"]{
                -webkit-border-radius: 4px;
                border-radius: 4px;
                border: none;
                /* border-top: 1px solid #fff; */
                -webkit-box-shadow: 0 0 1px rgba(0,0,0,.11), 0 1px 1px rgba(0,0,0,.3), inset 0 1px #fff, inset 0 0 1px rgba(255,255,255,.5);
                box-shadow: 0 0 1px rgba(0,0,0,.3), 0 1px 1px rgba(0,0,0,.3), inset 0 1px 0 #fff, inset 0 0 1px rgba(255,255,255,.5);
                background-color: #e0e9ec;
                background-image: -webkit-linear-gradient(bottom, #d7e3e7, #fff)!important;
                background-image: -moz-linear-gradient(bottom, #d7e3e7, #fff)!important;
                background-image: -ms-linear-gradient(bottom, #d7e3e7, #fff)!important;
                background-image: -o-linear-gradient(bottom, #d7e3e7, #fff)!important;
                background-image: linear-gradient(bottom, #d7e3e7, #fff)!important;
                color: #3f4b54;
                cursor: pointer;
                display: inline-block;
                font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
                font-weight: bold;
                font-size: 13px;
                /* line-height: 18px; */
                height: 29px;
                text-shadow: 0 1px rgba(255,255,255,0.7);
                text-decoration: none;
                position: relative;
                vertical-align: middle;
                -webkit-font-smoothing: antialiased;
                padding: 1px 13px 3px;
            }

            button[name=<?=$name?>]:hover{
                text-decoration: none;
                background: #f3f6f7!important;
                background-image: -webkit-linear-gradient(top, #f8f8f9, #f2f6f8)!important;
                background-image: -moz-linear-gradient(top, #f8f8f9, #f2f6f8)!important;
                background-image: -ms-linear-gradient(top, #f8f8f9, #f2f6f8)!important;
                background-image: -o-linear-gradient(top, #f8f8f9, #f2f6f8)!important;
                background-image: linear-gradient(top, #f8f8f9, #f2f6f8)!important;
            }
        </style>

        <button type='submit'
        <?=$input->getDisabled() ? 'disabled="disabled"': '';?>
                id="<?=$input->getValueId()?>"
                name="<?=$name?>"
                value="<?=urlencode($value)?>"><?=$input->getLabel()?></button><?php
    }
}