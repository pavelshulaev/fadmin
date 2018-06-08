<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:38
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Rover\Fadmin\Tab;
/**
 * Class Header
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Header extends Input
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__HEADER;

    /**
     * Header constructor.
     *
     * @param array $params
     * @param Tab   $tab
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
	public function __construct(array $params, Tab $tab)
	{
		if (!isset($params['name']))
			$params['name'] = self::$type;

		parent::__construct($params, $tab);
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
}