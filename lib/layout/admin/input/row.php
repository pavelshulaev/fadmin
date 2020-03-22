<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 14:55
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Rover\Fadmin\Layout\Admin\Input;

/**
 * Class Selectbox
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Row extends Input
{
    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw()
    {
        $this->showRowStart();
        $this->showCellStart('colspan=2');
        $this->showInput();
        $this->showCellEnd();
        $this->showRowEnd();
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        $inputs = $this->input->getInputs();

        ?><table style="width: 100%"><?php
        $this->showRowStart();
        $firstCell = true;
        foreach ($inputs as $input)
        {
            $displayInput = self::build($input);
            ob_start();
            $displayInput->showCells();
            $cells = ob_get_clean();

            $cells = preg_replace('#(width="\d+%")#usi', 'width="1%"', $cells);

            if ($firstCell)
            {
                $cells = $this->str_replace_once('width="1%"', 'width="50%"', $cells);
                $firstCell = false;
            } else {
                $cells = str_replace('adm-detail-content-cell-l', 'adm-detail-content-cell-r', $cells);
            }

            echo $cells;
        }
        ?><td style="width: auto"></td><?php
        $this->showRowEnd();
        ?></table><?php
    }

    protected function str_replace_once($search, $replace, $text){
        $pos = strpos($text, $search);
        return $pos!==false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
    }
}