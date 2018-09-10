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

use Rover\Fadmin\Layout\Admin\Input;
use \Rover\Fadmin\Inputs;
/**
 * Class SubTab
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class SubTab extends Input
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
        ?><table class="adm-detail-content-table edit-table"><?php

           /* if (strlen($this->input->getDefault())): ?>
            <h4><?=$this->input->getDefault()?></h4>
            <?php endif;*/

            $this->showInput();
        ?></table><?php
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
        if (!$this->input instanceof Inputs\SubTab)
            return;

        $inputs     = $this->input->getInputs();
        $inputsCnt  = count($inputs);

        for ($i = 0; $i < $inputsCnt; ++$i) {
            /** @var \Rover\Fadmin\Inputs\Input $input */
            $input = $inputs[$i];

            if (($input instanceof Inputs\SubTab)
                || ($input instanceof Inputs\SubTabControl))
                continue;

            if (!$input->isHidden())
                self::drawStatic($input);
        }
    }
}