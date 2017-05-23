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

		return $input;
	}

	/**
	 * @param            $name
	 * @param            $type
	 * @param null       $default
	 * @param bool|false $multiple
	 * @param bool|false $disabled
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function get($name, $type, $default = null, $multiple = false, $disabled = false)
	{
		if (!isset($name))
			throw new ArgumentNullException('name');

		if (!isset($type))
			throw new ArgumentNullException('type');

		return self::addFields([
			'type'      => $type,
			'name'      => $name,
			'default'   => $default,
			'multiple'  => $multiple,
			'disabled'  => $disabled
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
	public static function getTextarea($name, $default = null)
	{
		return self::get($name, InputAbstract::TYPE__TEXTAREA, $default);
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
	 * @param            $name
	 * @param string     $default
	 * @param bool|false $disabled
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getCheckbox($name, $default = 'Y', $disabled = false)
	{
		return self::get($name, InputAbstract::TYPE__CHECKBOX, $default == 'Y' ? 'Y' : 'N', false, $disabled);
	}

	/**
	 * @param $name
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getPresetName($name)
	{
		return self::get($name, InputAbstract::TYPE__PRESET_NAME);
	}

	/**
	 * @param $name
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getRemovePreset($name)
	{
		return self::get($name, InputAbstract::TYPE__REMOVE_PRESET);
	}

	/**
	 * @param            $name
	 * @param            $options
	 * @param null       $label
	 * @param null       $default
	 * @param bool|false $multiple
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getSelect($name, $options, $default = null, $multiple = false, $label = null)
	{
		$input = self::get($name, InputAbstract::TYPE__SELECTBOX, $default, $multiple);
		$input['options'] = $options;

		if ($label)
			$input['label'] = $label;

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

	/**
	 * @param            $name
	 * @param bool|false $multiple
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getIblock($name, $multiple = false)
	{
		return self::get($name, InputAbstract::TYPE__IBLOCK, null, $multiple);
	}
}