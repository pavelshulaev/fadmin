<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:16
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin;

use Bitrix\Main\Localization\Loc;
use \Rover\Fadmin\Inputs\Removepreset as RemovePresetInput;

Loc::loadMessages(__FILE__);

/**
 * Class Removepreset
 *
 * @package Rover\Fadmin\Layout\Admin
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Removepreset extends Submit
{
    /**
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function draw()
    {
        if (!$this->input instanceof RemovePresetInput)
            return;

        $presetId   = $this->input->getTab()->getPresetId();

        if (!$presetId)
            return;

        $this->showLabel(true);
        $this->showInput();
        $this->showHelp();

        $popup = $this->input->getPopup();

        if ($popup === false) return;

        $valueId    = $this->input->getValueId();
        $confirm    = $popup ? : Loc::getMessage('rover-fa__REMOVEPRESET_CONFIRM');

        $this->confirm($valueId, $confirm);
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        $presetId = $this->input->getTab()->getPresetId();

        if (!$presetId)
            return;

        $this->customInputName  = \Rover\Fadmin\Inputs\Removepreset::$type;
        $this->customInputValue = $this->input->getTab()->getSiteId() . RemovePresetInput::SEPARATOR . $presetId;

        parent::showInput();
    }
}