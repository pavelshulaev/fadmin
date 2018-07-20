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

	/** @var int */
	protected $min;

	/** @var int */
	protected $max;

    /**
     * Number constructor.
     *
     * @param array   $params
     * @param Options $options
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     */
	public function __construct(array $params, Options $options)
	{
		parent::__construct($params, $options);

		if (isset($params['min']))
		    $this->setMin($params['min']);

		if (isset($params['max']))
		    $this->setMax($params['max']);

		if (isset($params['placeholder']))
		    $this->setPlaceholder($params['placeholder']);
	}

    /**
     * @param $value
     * @return bool|mixed
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
	public function beforeSaveRequest(&$value)
	{
		// not integer
		if ($value != intval($value))
			$value = $this->default;

		// min
		if (!is_null($this->min) && $value < $this->min)
			$value = $this->default;

		// max
		if (!is_null($this->max) && $value > $this->max)
			$value = $this->default;

		return true;
	}

    /**
     * @return int
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param $min
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setMin($min)
    {
        $this->min = intval($min);

        return $this;
    }

    /**
     * @return int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param $max
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setMax($max)
    {
        $this->max = intval($max);

        return $this;
    }
}