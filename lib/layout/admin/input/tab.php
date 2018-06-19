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

use Rover\Fadmin\Layout\Admin\Input;
use \Rover\Fadmin\Inputs\Input as InputEngine;

/**
 * Class Tab
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Tab extends Input
{
    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw()
    {
        $this->showInput();
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
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