<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:33
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Rover\AmoCRM\Config\Options;
use Rover\Fadmin\Tab;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

/**
 * Class Selectbox
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Selectgroup extends Input
{
    public static $type = self::TYPE__SELECT_GROUP;

    /**
     * @var array
     */
    protected static $idCache = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * multiple selectbox size
     * @var int
     */
    protected $size = 7;

    /**
     * @param array $params
     * @param Tab   $tab
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public function __construct(array $params, Tab $tab)
    {
        parent::__construct($params, $tab);

        if (isset($params['options']))
            $this->options = $params['options'];

        if (isset($params['size']) && intval($params['size']))
            $this->size = intval($params['size']);
        elseif ($params['multiple'])
            $this->size = count($this->options) > $this->size
                ? $this->size
                : count($this->options);
        else
            $this->size = 1;

        $this->addEventHandler(self::EVENT__BEFORE_SAVE_VALUE, [$this,  'beforeSaveValue']);
    }

    /**
     * @param bool $empty
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showLabel($empty = false)
    {
        if ($this->multiple)
            parent::showMultiLabel();
        else
            parent::showLabel($empty);
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        echo $this->getList();
    }

    /**
     * @param array $options
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getGroupName()
    {
        return $this->name . '_group';
    }

    /**
     * @return string
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getGroupValueName()
    {
        return Options::getFullName($this->getGroupName(),
            $this->tab->getPresetId(), $this->tab->getSiteId());
    }

    /**
     * @return Input
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getGroupInput()
    {
        $params = [
            'name' => $this->getGroupName(),
            'type' => self::TYPE__HIDDEN
        ];

        return self::factory($params, $this->tab);
    }

    /**
     * @return array|int|null|string
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getGroupValue()
    {
        return $this->getGroupInput()->getValue();
    }

    /**
     * @param $value
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setGroupValue($value)
    {
        return $this->getGroupInput()->setValue($value);
    }

    /**
     * @return int|null|string
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function calcGroupValue()
    {
        $searchValue = $this->value;
        if (!is_array($searchValue))
            $searchValue = [$searchValue];

        reset($this->options);

        if (!count($searchValue))
            return key($this->options);

        foreach ($this->options as $key => $group)
            if (count(array_intersect($searchValue, array_keys($group['options']))))
                return $key;

        reset($this->options);

        return key($this->options);
    }

    /**
     * @param array $params = [
     *  'options' - options' map
     *  'value' - value(s)
     *  'multiple' - multiple
     *  'group_name'
     *  'item_name'
     *  'on_change_group'   - additional js-handler
     *  'on_change_item'    - additional js-handler
     *  'group_additional'      - additional group params
     *  'item_additional'       - additional item params
     * ]
     * @return string
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getList()
    {
        if (empty($this->options))
            return '';

        $optionsId  = md5(serialize($this->options));

        $value      = empty($this->value) ? [] : $this->value;
        if (!is_array($value))
            $value = [$value];

        // change group script
        $html = '';

        if(!isset(self::$idCache[$optionsId]))
            $html .= '
			<script type="text/javascript">
                function OnType_'.$optionsId.'_Changed(typeSelect, selectID)
                {
                    var items       = '.\CUtil::PhpToJSObject($this->options).';
                    var selected    = BX(selectID);
                    
                    if(!!selected)
                    {
                        for(var i=selected.length-1; i >= 0; i--){
                            selected.remove(i);
                        }
                            
                        for(var j in items[typeSelect.value]["options"])
                        {
                            var newOption = new Option(items[typeSelect.value]["options"][j], j, false, false);
                            selected.options.add(newOption);
                        }
                    }
                }
			</script>
			';

        $groupValue     = $this->getGroupValue() ?: $this->calcGroupValue();
        $valueName      = $this->getValueName();
        $valueGroupName = $this->getGroupValueName();
        $onChangeGroup  = 'OnType_'.$optionsId.'_Changed(this, \''.\CUtil::JSEscape($valueName).'\');';

        $html .= '<select 
                name="' . $valueGroupName . '"
                id="' . $valueGroupName . '"
                onchange="'.htmlspecialcharsbx($onChangeGroup).'">'."\n";

        foreach($this->options as $key => $optionValue)
            $html .= '<option value="'.htmlspecialcharsbx($key).'"'.($groupValue==$key? ' selected': '').'>'.htmlspecialcharsEx($optionValue['name']).'</option>'."\n";

        $html .= "</select>\n";
        $html .= "&nbsp;\n";
        $html .= '<select 
                    name="' . $valueName . ($this->multiple
                        ? '[]" multiple="multiple" size="' . $this->size . '" '
                        : '"')
                    . '
                    id="' . $valueName . '">'."\n";

        if (!is_null($groupValue))
            foreach($this->options[$groupValue]['options'] as $key => $optionValue)
                $html .= '<option value="'.htmlspecialcharsbx($key).'"'.(in_array($key, $value)? ' selected': '').'>'.htmlspecialcharsEx($optionValue).'</option>'."\n";

        $html .= "</select>\n";

        return $html;
    }

    /**
     * not save
     * @param Event $event
     * @return EventResult
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function beforeSaveValue(Event $event)
    {
        if ($event->getSender() !== $this)
            return $this->getEvent()->getErrorResult($this);

        $this->getGroupInput()->setValueFromRequest();

        return $this->getEvent()->getSuccessResult($this);
    }
}