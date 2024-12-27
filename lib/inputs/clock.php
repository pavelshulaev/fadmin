<?php

namespace Rover\Fadmin\Inputs;

/**
 * Class Clock
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Clock extends Input
{
    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeSaveRequest(&$value): bool
    {
        if (!self::isValid($value)) {
            $value = $this->getDefault();
        }

        return true;
    }

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function isValid($value): bool
    {
        if (!preg_match('#^(\d{1,2}):(\d{2})$#uUsi', $value)) {
            return false;
        }

        [$hours, $minutes] = explode(':', $value);
        $hours   = intval($hours);
        $minutes = intval($minutes);

        return ($hours >= 0)
            && ($hours <= 23)
            && ($minutes >= 0)
            && ($minutes <= 59);
    }
}