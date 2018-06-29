<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:14
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Bitrix\Main\Loader;
use Rover\Fadmin\Layout\Admin\Input;

/**
 * Class Textarea
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Textarea extends Input
{
    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\LoaderException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        if (!$this->input instanceof \Rover\Fadmin\Inputs\Textarea)
            return;

        if ($this->input->isHtmlEditor()
            && Loader::includeModule("fileman"))
        {
            $fieldId    = $this->input->getFieldId();
            $editor     = new \CHTMLEditor;
            $res        = array_merge(
                array(
                    'useFileDialogs' => false,
                    'height' => 200,
                    'minBodyWidth' => 350,
                    'normalBodyWidth' => 555,
                    'bAllowPhp' => false,
                    'limitPhpAccess' => false,
                    'showTaskbars' => false,
                    'showNodeNavi' => false,
                    'askBeforeUnloadPage' => true,
                    'bbCode' => $this->input->isHtmlEditorBB(),
                    'siteId' => SITE_ID,
                    'autoResize' => true,
                    'autoResizeOffset' => 40,
                    'saveOnBlur' => true,
                    'controlsMap' => array(
                        array('id' => 'Bold',  'compact' => true, 'sort' => 80),
                        array('id' => 'Italic',  'compact' => true, 'sort' => 90),
                        array('id' => 'Underline',  'compact' => true, 'sort' => 100),
                        array('id' => 'Strikeout',  'compact' => true, 'sort' => 110),
                        array('id' => 'RemoveFormat',  'compact' => true, 'sort' => 120),
                        array('id' => 'Color',  'compact' => true, 'sort' => 130),
                        array('id' => 'FontSelector',  'compact' => false, 'sort' => 135),
                        array('id' => 'FontSize',  'compact' => false, 'sort' => 140),
                        array('separator' => true, 'compact' => false, 'sort' => 145),
                        array('id' => 'OrderedList',  'compact' => true, 'sort' => 150),
                        array('id' => 'UnorderedList',  'compact' => true, 'sort' => 160),
                        array('id' => 'AlignList', 'compact' => false, 'sort' => 190),
                        array('separator' => true, 'compact' => false, 'sort' => 200),
                        array('id' => 'InsertLink',  'compact' => true, 'sort' => 210, 'wrap' => 'bx-b-link-'.$fieldId),
                        array('id' => 'InsertImage',  'compact' => false, 'sort' => 220),
                        array('id' => 'InsertVideo',  'compact' => true, 'sort' => 230, 'wrap' => 'bx-b-video-'.$fieldId),
                        array('id' => 'InsertTable',  'compact' => false, 'sort' => 250),
                        array('id' => 'Code',  'compact' => true, 'sort' => 260),
                        array('id' => 'Quote',  'compact' => true, 'sort' => 270, 'wrap' => 'bx-b-quote-'.$fieldId),
                        array('id' => 'Smile',  'compact' => false, 'sort' => 280),
                        array('separator' => true, 'compact' => false, 'sort' => 290),
                        array('id' => 'Fullscreen',  'compact' => false, 'sort' => 310),
                        array('id' => 'BbCode',  'compact' => true, 'sort' => 340),
                        array('id' => 'More',  'compact' => true, 'sort' => 400)
                    )
                ),
                array(
                    'name'          => $this->input->getFieldName(),
                    'inputName'     => $this->input->getFieldName(),
                    'id'            => $fieldId,
                    'width'         => '100%',
                    'placeholder'   => $this->input->getPlaceholder(),
                    'content'       => htmlspecialcharsBack($this->input->getValue()),
                )
            );
            $editor->show($res);
        } else {
            ?><textarea
            <?=$this->input->isDisabled() ? 'disabled="disabled"': '';?>
            id="<?=$this->input->getFieldId()?>"
            rows="<?=$this->input->getRows()?>"
            cols="<?=$this->input->getCols()?>"
            <?=strlen($this->input->getPlaceholder()) ? " placeholder='{$this->input->getPlaceholder()}' " : ''?>
            name="<?=$this->input->getFieldName()?>"><?=$this->input->getValue()?></textarea><?php
        }
    }

    /**
     * @param bool $empty
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showLabel($empty = false)
    {
        $valueId = $this->input->getFieldId();
        ?>
        <tr>
        <td
            width="50%"
            style="vertical-align: top; padding-top: 7px;"
            class="adm-detail-valign-top">
            <?php if (!$empty) : ?>
                <label for="<?=$valueId?>"><?=$this->input->getLabel()?>:</label>
            <?php endif; ?>
        </td>
        <td width="50%"><?php
    }

}