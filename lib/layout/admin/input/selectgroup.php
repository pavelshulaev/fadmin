<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:52
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;

/**
 * Class Selectgroup
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Selectgroup extends Selectbox
{
    /** @var array */
    protected static array $idCache = [];

    /**
     * @return void
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput(): void
    {
        echo $this->getList();
    }

    /**
     * @return string
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getList(): string
    {
        if (!$this->input instanceof \Rover\Fadmin\Inputs\Selectgroup) {
            return '';
        }

        $options = $this->input->getOptions();

        if (empty($options)) {
            return '-';
        }

        $optionsId = md5(serialize($options));

        $value = $this->input->getValue();
        $value = empty($value) ? [] : $value;

        if (!is_array($value)) {
            $value = [$value];
        }

        // change group script
        $html = '';

        if (!isset(self::$idCache[$optionsId])) {
            $resultItems = [];
        }

        // for keeping sort
        foreach ($options as $itemId => $itemValue) {
            $item = [
                'id'   => $itemId,
                'name' => $itemValue['name']
            ];


            if (isset($itemValue['options'])) {
                $itemOptions = [];
                foreach ($itemValue['options'] as $optionId => $optionName) {
                    $itemOptions[] = ['id' => $optionId, 'name' => $optionName];
                }

                $item['options'] = $itemOptions;
            }

            $resultItems[] = $item;
        }

        $html .= '
			<script type="text/javascript">
                function OnType_' . $optionsId . '_Changed(typeSelect, selectID)
                {
                    let items       = ' . \CUtil::PhpToJSObject($resultItems) . ';
                    let selected    = BX(selectID), options;
                  
                    if(!!selected)
                    {
                        for(let i=selected.length-1; i >= 0; i--){
                            selected.remove(i);
                        }
                        
                        // search selected group
                        for(let k in items)
                        {
                            if ((items[k]["id"] == typeSelect.value)
                                && (items[k]["options"]))
                            {
                                options = items[k]["options"];
                            }
                        }
                        
                        if (!!options) {
                            for(let j in options)
                            {
                                let newOption = new Option(options[j]["name"], options[j]["id"], false, false);
                                selected.options.add(newOption);
                            }
                        }
                    }
                }
			</script>
			';

        $groupValue     = $this->input->getGroupValue() ?: $this->input->calcGroupValue();
        $valueName      = $this->input->getFieldName();
        $valueGroupName = $this->input->getGroupValueName();
        $onChangeGroup  = 'OnType_' . $optionsId . '_Changed(this, \'' . \CUtil::JSEscape($valueName) . '\');';

        $html .= '<select 
                ' . ($this->input->isDisabled() ? 'disabled="disabled"' : '') . '
                name="' . $valueGroupName . '"
                id="' . $valueGroupName . '"
                onchange="' . htmlspecialcharsbx($onChangeGroup) . '">' . "\n";

        foreach ($options as $key => $optionValue) {
            $html .= '<option value="' . htmlspecialcharsbx($key) . '"' . ($groupValue == $key ? ' selected' : '') . '>'
                . htmlspecialcharsEx($optionValue['name'] ?? $key)
                . '</option>' . "\n";
        }

        $html .= "</select>\n";
        $html .= "&nbsp;\n";
        $html .= '<select
                    ' . ($this->input->isDisabled() ? ' disabled="disabled" ' : '') . ' 
                    ' . ($this->input->isRequired() ? ' required="required" ' : '') . ' 
                    name="' . $valueName . ($this->input->isMultiple()
                ? '[]" multiple="multiple" size="' . $this->input->getSize() . '" '
                : '"')
            . ' id="' . $valueName . '">' . "\n";

        if ($groupValue) {
            foreach ($options[$groupValue]['options'] as $key => $optionValue) {
                $html .= '<option value="' . htmlspecialcharsbx($key) . '"' . (in_array($key,
                        $value) ? ' selected' : '') . '>'
                    . htmlspecialcharsEx($optionValue)
                    . '</option>' . "\n";
            }
        }

        $html .= "</select>\n";

        return $html;
    }
}