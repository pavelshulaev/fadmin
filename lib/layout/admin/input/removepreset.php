<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:16
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Bitrix\Main\Localization\Loc;
use \Rover\Fadmin\Inputs\Removepreset as RemovePresetInput;

Loc::loadMessages(__FILE__);

/**
 * Class Removepreset
 *
 * @package Rover\Fadmin\Layout\Preset\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Removepreset extends Submit
{
    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw()
    {
        if (!$this->input instanceof RemovePresetInput)
            return;

        $presetId = $this->input->getTab()->getPresetId();

        if (!$presetId)
            return;

        $this->showRowStart();
        $this->showCells();
        $this->showRowEnd();
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showCells()
    {
        $this->showLabelCell('width="50%" class="adm-detail-content-cell-l" style="vertical-align: top; padding-top: 7px;"', true);
        $this->showInputCell('width="50%" class="adm-detail-content-cell-r"');
    }


    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        if (!$this->input instanceof RemovePresetInput)
            return;

        $presetId = $this->input->getPresetId();

        if (!$presetId)
            return;

        $popup = $this->input->getPopup();

        if (($popup !== false) && !strlen($popup))
            $popup = Loc::getMessage('rover-fa__REMOVEPRESET_CONFIRM');

        $this->customInputName  = RemovePresetInput::getType();
        $this->customInputValue = $this->input->getSiteId() . RemovePresetInput::SEPARATOR . $presetId;
        $this->customInputId    = $this->input->getFieldId();
        $this->customPopup      = $popup;

        parent::showInput();
    }
}