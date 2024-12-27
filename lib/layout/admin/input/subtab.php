<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 14.06.2018
 * Time: 8:11
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Layout\Admin\Input;
use Rover\Fadmin\Inputs;
/**
 * Class SubTab
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class SubTab extends Input
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
        ?><table class="adm-detail-content-table edit-table"><?php

           /* if (strlen($this->input->getDefault())): ?>
            <h4><?=$this->input->getDefault()?></h4>
            <?php endif;*/

            $this->showInput();
        ?></table><?php
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
        if (!$this->input instanceof Inputs\SubTab)
            return;

        $inputs     = $this->input->getInputs();
        $inputsCnt  = count($inputs);

        for ($i = 0; $i < $inputsCnt; ++$i) {
            /** @var Inputs\Input $input */
            $input = $inputs[$i];

            if (($input instanceof Inputs\SubTab)
                || ($input instanceof Inputs\SubTabControl))
                continue;

            if (!$input->isHidden())
                self::drawStatic($input);
        }
    }
}