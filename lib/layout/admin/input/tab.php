<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 19.06.2018
 * Time: 9:32
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Layout\Admin\Input;
use Rover\Fadmin\Inputs\Input as InputEngine;

/**
 * Class Tab
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Tab extends Input
{
    /**
     * @return void
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw(): void
    {
        $this->showInput();
    }

    /**
     * @return void
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput(): void
    {
        if (!$this->input instanceof \Rover\Fadmin\Inputs\Tab)
            return;

        /** @var InputEngine[] $inputs */
        $inputs     = $this->input->getInputs();
        $inputsCnt  = count($inputs);

        for ($i = 0; $i < $inputsCnt; ++$i){
            $input = $inputs[$i];
            $input->loadValue();

            if (!$input->isHidden())
                self::drawStatic($input);
        }
    }
}