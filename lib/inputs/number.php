<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:26
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Inputs\Params\Placeholder;
use Rover\Fadmin\Options;

/**
 * Class Number
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Number extends Text
{
    use Placeholder;

    protected int $min;
    protected int $max;

    /**
     * Number constructor.
     *
     * @param array $params
     * @param Options $options
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     */
    public function __construct(array $params, Options $options)
    {
        parent::__construct($params, $options);

        if (isset($params['min'])) {
            $this->setMin($params['min']);
        }

        if (isset($params['max'])) {
            $this->setMax($params['max']);
        }

        if (isset($params['placeholder'])) {
            $this->setPlaceholder($params['placeholder']);
        }
    }

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    public function beforeSaveRequest(&$value): bool
    {
        // not integer
        if ($value != intval($value)) {
            $value = $this->default;
        }

        // min
        if (isset($this->min) && $value < $this->min) {
            $value = $this->default;
        }

        // max
        if (isset($this->max) && $value > $this->max) {
            $value = $this->default;
        }

        return true;
    }

    /**
     * @return int
     */
    public function getMin(): int
    {
        return $this->min;
    }

    /**
     * @param $min
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setMin($min): static
    {
        $this->min = intval($min);

        return $this;
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @param $max
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setMax($max): static
    {
        $this->max = intval($max);

        return $this;
    }
}