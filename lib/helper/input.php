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
	public static function get($type, $name, $default = null, $multiple = false, $disabled = false)
	{
		if (!isset($type))
			throw new ArgumentNullException('type');

        if (!isset($name))
            throw new ArgumentNullException('name');

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
	public static function getText($name, $default = '')
	{
		return self::get(InputAbstract::TYPE__TEXT, $name, $default);
	}

	/**
	 * @param      $name
	 * @param null $default
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getTextarea($name, $default = '')
	{
		return self::get(InputAbstract::TYPE__TEXTAREA, $name, $default);
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
		return self::get(InputAbstract::TYPE__NUMBER, $name, $default);
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
		return self::get(InputAbstract::TYPE__CHECKBOX, $name, $default == 'Y' ? 'Y' : 'N', false, $disabled);
	}

	/**
	 * @param $name
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getPresetName($name)
	{
		return self::get(InputAbstract::TYPE__PRESET_NAME, $name);
	}

	/**
	 * @param $name
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getRemovePreset($name)
	{
		return self::get(InputAbstract::TYPE__REMOVE_PRESET, $name);
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
		$input = self::get(InputAbstract::TYPE__SELECTBOX, $name, $default, $multiple);
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
	 * @param      $name
	 * @param null $label
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getCustom($name, $label = null)
	{
		$result = self::get(InputAbstract::TYPE__CUSTOM, $name);

		$label = trim($label);
		if (strlen($label))
			$result['label'] = $label;

		return $result;
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
		return self::get(InputAbstract::TYPE__IBLOCK, $name, null, $multiple);
	}

	/**
	 * @param        $label
	 * @param string $default
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getLabel($label, $default = '')
	{
		return [
			'type'      => InputAbstract::TYPE__HEADER,
			'label'     => $label,
			'default'   => $default
		];
	}

    /**
     * @param        $name
     * @param string $default
     * @return array
     * @author Pavel Shulaev (http://rover-it.me)
     */
	public static function getClock($name, $default = '0:00')
    {
        return self::get(InputAbstract::TYPE__CLOCK, $name, $default);
    }

    /**
     * @param $name
     * @param $default
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (http://rover-it.me)
     */
	public static function getSubmit($name, $default)
	{
		// button's name
		$default = trim($default);
		if (!strlen($default))
			throw new ArgumentNullException('default');

		return self::get(InputAbstract::TYPE__SUBMIT, $name, $default);
	}

    /**
     * @param      $name
     * @param      $default
     * @param bool $popup
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (http://rover-it.me)
     */
	public static function getAddPreset($name, $default, $popup = false)
	{
		$result = self::get(InputAbstract::TYPE__ADD_PRESET, $name);

		$default = trim($default);
		if (!strlen($default))
			throw new ArgumentNullException('default');

		$result['default']  = $default;
		$result['popup']    = $popup;

		return $result;
	}
}