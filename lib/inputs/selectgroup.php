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

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;

/**
 * Class Selectbox
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Selectgroup extends Selectbox
{
    /**
     * @return string
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getGroupName(): string
    {
        return $this->name . '_group';
    }

    /**
     * @return string
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getGroupValueName(): string
    {
        return self::getFullPath($this->getGroupName(),
            $this->getPresetId(), $this->getSiteId());
    }

    /**
     * @return Input
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getGroupInput(): Input
    {
        $params = array(
            'name' => $this->getGroupName(),
            'type' => Hidden::getType()
        );

        return self::build($params, $this->optionsEngine, $this->parent);
    }

    /**
     * @return array|string
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getGroupValue()
    {
        return $this->getGroupInput()->getValue();
    }

    /**
     * @param $value
     * @return Input
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setGroupValue($value): Input
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
            $searchValue = array($searchValue);

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
     * @param $value
     * @return bool
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    public function beforeSaveValue(&$value): bool
    {
        $this->getGroupInput()->setValueFromRequest();

        return true;
    }
}