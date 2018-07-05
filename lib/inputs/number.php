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
     */
	public function __construct(array $params, Options $options)
	{
		parent::__construct($params, $options);

		if (isset($params['min']))
			$this->min = (int)$params['min'];

		if (isset($params['max']))
			$this->max = (int)$params['max'];

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
     * @param int $min
     */
    public function setMin($min)
    {
        $this->min = $min;
    }

    /**
     * @return int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param int $max
     */
    public function setMax($max)
    {
        $this->max = $max;
    }
}