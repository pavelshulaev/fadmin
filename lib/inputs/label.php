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

use Rover\Fadmin\Options;

/**
 * Class Label
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Label extends Input
{
    /**
     * Label constructor.
     *
     * @param array      $params
     * @param Options    $options
     * @param Input|null $parent
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
	public function __construct(array $params, Options $options, Input $parent = null)
	{
		if (!isset($params['name']))
			$params['name'] = self::getType();

		parent::__construct($params, $options, $parent);
	}

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
	public function beforeSaveValue(&$value)
	{
		return false;
	}

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function beforeLoadValue()
    {
        return false;
    }
}