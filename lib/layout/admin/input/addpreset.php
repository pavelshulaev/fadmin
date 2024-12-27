<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:07
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\Localization\Loc;
use Rover\Fadmin\Inputs\Addpreset as AddPresetInput;

Loc::loadMessages(__FILE__);

/**
 * Class Addpreset
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Addpreset extends Submit
{
    /**
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showCells(): void
    {
        $this->showLabelCell('width="50%" class="adm-detail-content-cell-l" style="vertical-align: top; padding-top: 7px;"',
            true);
        $this->showInputCell('width="50%" class="adm-detail-content-cell-r"');
    }

    /**
     * @return void
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput(): void
    {
        if (!$this->input instanceof AddPresetInput) {
            return;
        }

        $this->customInputName  = AddPresetInput::getType();
        $this->customInputValue = $this->input->getSiteId() . AddPresetInput::SEPARATOR . $this->input->getDefault();

        parent::showInput();

        $popup = $this->input->getPopup();

        if ($popup === false) {
            return;
        }

        $text    = $popup ?: Loc::getMessage('rover-fa__ADDPRESET_TEXT');
        $default = $this->input->getDefault() ?: Loc::getMessage('rover-fa__ADDPRESET_DEFAULT');

        ?>
        <script>
            (function () {
                document.getElementById('<?=$this->input->getFieldId()?>').onclick = function () {
                    let presetName = prompt('<?=$text ?>', '<?=$default?>');

                    if (presetName == null)
                        return false;

                    if (!presetName.length) {
                        alert('<?=Loc::getMessage('rover-fa__ADDPRESET_ALERT')?>');
                        return false;
                    }

                    this.setAttribute('value', '<?=$this->input->getSiteId() . AddPresetInput::SEPARATOR?>' + presetName);
                    return true;
                }
            })();
        </script>
        <?php
    }
}