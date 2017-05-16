<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 16.05.2017
 * Time: 16:31
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Helper;

use Bitrix\Main\ArgumentNullException;
use Rover\Fadmin\Inputs\Input as InputAbstract;
use Bitrix\Main\Localization\Loc;

/**
 * Class Input
 *
 * @package Rover\Fadmin\Helper
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Input
{
	/**
	 * @param array $input
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected static function addFields(array $input)
	{
		if (!isset($input['type']))
			throw new ArgumentNullException('type');

		if (!isset($input['name']))
			throw new ArgumentNullException('name');

		if (!isset($input['label']))
			$input['label'] = Loc::getMessage($input['name'] . '_label');

		if (!isset($input['help']))
			$input['help'] = Loc::getMessage($input['name'] . '_help');

		if (!isset($input['default']))
			$input['default'] = Loc::getMessage($input['name'] . '_default');

		return $input;
	}

	/**
	 * @param      $name
	 * @param      $type
	 * @param null $default
	 * @param null $label
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function get($name, $type, $default = null, $label = null)
	{
		if (!isset($name))
			throw new ArgumentNullException('name');

		if (!isset($type))
			throw new ArgumentNullException('type');

		return self::addFields([
			'type'      => $type,
			'name'      => $name,
			'default'   => $default,
			'label'     => $label
		]);
	}

	/**
	 * @param      $name
	 * @param null $default
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getText($name, $default = null)
	{
		return self::get($name, InputAbstract::TYPE__TEXT, $default);
	}

	/**
	 * @param      $name
	 * @param null $default
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getNumber($name, $default = null)
	{
		return self::get($name, InputAbstract::TYPE__NUMBER, $default);
	}

	/**
	 * @param        $name
	 * @param string $default
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getCheckbox($name, $default = 'Y')
	{
		return self::get($name, InputAbstract::TYPE__CHECKBOX, $default == 'Y' ? 'Y' : 'N');
	}

	/**
	 * @param      $name
	 * @param      $options
	 * @param null $label
	 * @param null $default
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getSelect($name, $options, $label = null, $default = null)
	{
		$input = self::get($name, InputAbstract::TYPE__SELECTBOX);
		$input['options'] = $options;

		if (!is_null($label))
			$input['label'] = $label;

		if (!is_null($default))
			$input['default'] = $default;

		return $input;
	}

	/**
	 * @param $label
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getHeader($label)
	{
		return [
			'type'  => InputAbstract::TYPE__HEADER,
			'label' => $label,
		];
	}

	/**
	 * @param $name
	 * @param $label
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getCustom($name, $label)
	{
		return self::get($name, InputAbstract::TYPE__CUSTOM, null, $label);
	}
}