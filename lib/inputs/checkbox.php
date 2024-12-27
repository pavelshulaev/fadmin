<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:24
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Main\ArgumentNullException;

/**
 * Class Checkbox
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Checkbox extends Input
{
    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeSaveRequest(&$value): bool
    {
        if ($value !== "Y") {
            $value = "N";
        }

        return true;
    }

    /**
     * @param $value
     * @return void
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    public function afterLoadValue(&$value): void
    {
        $value = $value == 'Y' ? 'Y' : 'N';
    }

    /**
     * @param $value
     * @return bool
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeGetValue(&$value): bool
    {
        if ($this->optionsEngine->settings->getBoolCheckbox()) {
            $value = $value == 'Y';
        }

        return true;
    }
}