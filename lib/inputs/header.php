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

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Options;
/**
 * Class Header
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Header extends Input
{
    /**
     * Header constructor.
     *
     * @param array      $params
     * @param Options    $options
     * @param Input|null $parent
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
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
	public function beforeSaveValue(&$value): bool
    {
		return false;
	}
    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function beforeLoadValue(): bool
    {
        return false;
    }

}