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

		if (empty($input['label']))
			$input['label'] = Loc::getMessage($input['name'] . '_label');

		if (empty($input['help']))
			$input['help'] = Loc::getMessage($input['name'] . '_help');

		return $input;
	}

    /**
     * @param      $type
     * @param      $name
     * @param null $default
     * @param bool $multiple
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function get($type, $name, $default = null, $multiple = false, $disabled = false, $label = null, $help = null)
	{
		if (!isset($type))
			throw new ArgumentNullException('type');

        if (!isset($name))
            throw new ArgumentNullException('name');

        return self::addFields(array(
			'type'      => $type,
			'name'      => $name,
			'default'   => $default,
			'multiple'  => $multiple,
			'disabled'  => $disabled,
            'label'     => $label,
            'help'      => $help
        ));
	}

    /**
     * @param        $name
     * @param string $default
     * @param null   $label
     * @param bool   $disabled
     * @param null   $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getText($name, $default = '', $label = null, $disabled = false, $help = null)
	{
		return self::get(InputAbstract::TYPE__TEXT, $name, $default, false, $disabled, $label, $help);
	}

    /**
     * @param        $name
     * @param string $default
     * @param null   $cols
     * @param null   $rows
     * @param bool   $disabled
     * @param null   $label
     * @param null   $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getTextarea($name, $default = '', $cols = null, $rows = null, $disabled = false, $label = null, $help = null)
	{
		$textarea = self::get(InputAbstract::TYPE__TEXTAREA, $name, $default, false, $disabled, $label, $help);

		if (!is_null($cols))
		    $textarea['cols']   = intval($cols);

		if (!is_null($rows))
		    $textarea['rows']   = intval($rows);

		return $textarea;
	}

    /**
     * @param      $name
     * @param null $default
     * @param null $label
     * @param bool $disabled
     * @param null $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getNumber($name, $default = null, $label = null, $disabled = false, $help = null)
	{
	    return self::get(InputAbstract::TYPE__NUMBER, $name, $default, false, $disabled, $label, $help);
	}

    /**
     * @param        $name
     * @param string $default
     * @param bool   $disabled
     * @param null   $label
     * @param null   $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getCheckbox($name, $default = 'Y', $disabled = false, $label = null, $help = null)
	{
		return self::get(InputAbstract::TYPE__CHECKBOX, $name, $default == 'Y' ? 'Y' : 'N', false, $disabled, $label, $help);
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
     * @param      $name
     * @param bool $popup
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getRemovePreset($name, $popup = false)
	{
		$input = self::get(InputAbstract::TYPE__REMOVE_PRESET, $name);
		if ($popup !== false)
		    $input['popup'] = $popup;

        return $input;
	}

    /**
     * @param       $name
     * @param array $options
     * @param null  $default
     * @param bool  $multiple
     * @param null  $label
     * @param bool  $disabled
     * @param null  $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getSelect($name, array $options = array(), $default = null, $multiple = false, $label = null, $disabled = false, $help = null)
	{
		$input = self::get(InputAbstract::TYPE__SELECTBOX, $name, $default, $multiple, $disabled, $label, $help);
		$input['options'] = $options;

		return $input;
	}

    /**
     * @param       $name
     * @param array $options
     * @param null  $default
     * @param bool  $multiple
     * @param null  $label
     * @param bool  $disabled
     * @param null  $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getSelectGroup($name, array $options = array(), $default = null, $multiple = false, $label = null, $disabled = false, $help = null)
	{
		$input = self::get(InputAbstract::TYPE__SELECT_GROUP, $name, $default, $multiple, $disabled, $label, $help);
		$input['options'] = $options;

		return $input;
	}

    /**
     * @param       $name
     * @param array $options
     * @param null  $default
     * @param bool  $disabled
     * @param null  $label
     * @param null  $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getRadio($name, array $options = array(), $default = null, $disabled = false, $label = null, $help = null)
	{
		$input = self::get(InputAbstract::TYPE__RADIO, $name, $default, false, $disabled, $label, $help);
		$input['options'] = $options;

		return $input;
	}

	/**
	 * @param $label
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getHeader($label)
	{
		return array(
			'type'  => InputAbstract::TYPE__HEADER,
			'label' => $label,
        );
	}

    /**
     * @param      $name
     * @param null $label
     * @param null $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getCustom($name, $label = null, $help = null)
	{
		return self::get(InputAbstract::TYPE__CUSTOM, $name, null, false, false, $label, $help);
	}

    /**
     * @param      $name
     * @param bool $multiple
     * @param null $default
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getIblock($name, $multiple = false, $default = null, $disabled = false, $label = null, $help = null)
	{
		return self::get(InputAbstract::TYPE__IBLOCK, $name, $default, $multiple, $disabled, $label, $help);
	}

    /**
     * @param        $label
     * @param string $default
     * @param string $help
     * @return array
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getLabel($label, $default = '', $help = '')
	{
		return array(
			'type'      => InputAbstract::TYPE__LABEL,
			'label'     => $label,
			'default'   => $default,
            'help'      => $help
        );
	}

    /**
     * @param        $name
     * @param string $default
     * @param bool   $disabled
     * @param null   $label
     * @param null   $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getClock($name, $default = '0:00', $disabled = false, $label = null, $help = null)
    {
        return self::get(InputAbstract::TYPE__CLOCK, $name, $default, false, $disabled, $label, $help);
    }

    /**
     * @param      $name
     * @param      $default
     * @param bool $popup
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getSubmit($name, $default, $popup = false, $disabled = false, $label = null, $help = null)
	{
		// button's name
		$default = trim($default);
		if (!strlen($default))
			throw new ArgumentNullException('default');

		$submit = self::get(InputAbstract::TYPE__SUBMIT, $name, $default, false, $disabled, $label, $help);
		$submit['popup'] = $popup;

		return $submit;
	}

    /**
     * @param      $name
     * @param      $default
     * @param bool $popup
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getAddPreset($name, $default, $popup = false)
	{
		$result = self::get(InputAbstract::TYPE__ADD_PRESET, $name);

		$default = trim($default);
		if (!strlen($default))
			throw new ArgumentNullException('default');

		$result['id']       = $name;
		$result['default']  = $default;
		$result['popup']    = $popup;

		return $result;
	}

    /**
     * @param        $name
     * @param string $default
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (http://rover-it.me)
     */
	public static function getHidden($name, $default = '')
    {
        $name = trim($name);
        if (!$name)
            throw new ArgumentNullException('name');

        return array(
            'type'      => InputAbstract::TYPE__HIDDEN,
            'name'      => $name,
            'default'   => $default
        );
    }
}