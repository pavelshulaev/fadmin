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
use Rover\Fadmin\Inputs\Addpreset;
use Rover\Fadmin\Inputs\Checkbox;
use Rover\Fadmin\Inputs\Clock;
use Rover\Fadmin\Inputs\Custom;
use Rover\Fadmin\Inputs\Header;
use Rover\Fadmin\Inputs\Hidden;
use Rover\Fadmin\Inputs\Iblock;
use Rover\Fadmin\Inputs\Label;
use Rover\Fadmin\Inputs\Number;
use Rover\Fadmin\Inputs\PresetName;
use Rover\Fadmin\Inputs\Radio;
use Rover\Fadmin\Inputs\Removepreset;
use Rover\Fadmin\Inputs\Selectbox;
use Rover\Fadmin\Inputs\Selectgroup;
use Rover\Fadmin\Inputs\Submit;
use Rover\Fadmin\Inputs\Text;
use Rover\Fadmin\Inputs\Textarea;
use Bitrix\Main\Localization\Loc;
/**
 * Class Input
 *
 * @package Rover\Fadmin\Helper
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class InputFactory
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

		if (is_null($input['default']))
			$input['default'] = Loc::getMessage($input['name'] . '_default');

		if (empty($input['multiple']))
            $input['multiple'] = false;

		if (empty($input['disabled']))
            $input['disabled'] = false;

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
	public static function getText($name, $default = '', $disabled = false, $label = null, $help = null)
	{
		return self::get(Text::getType(), $name, $default, false, $disabled, $label, $help);
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
		$textarea = self::get(Textarea::getType(), $name, $default, false, $disabled, $label, $help);

        $cols = intval($cols);
        $rows = intval($rows);

		if ($cols) $textarea['cols'] = $cols;
		if ($rows) $textarea['rows'] = $rows;

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
	public static function getNumber($name, $default = null, $disabled = false, $label = null, $help = null)
	{
	    return self::get(Number::getType(), $name, $default, false, $disabled, $label, $help);
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
		return self::get(Checkbox::getType(), $name, $default == 'Y' ? 'Y' : 'N', false, $disabled, $label, $help);
	}

    /**
     * @param      $name
     * @param null $default
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getPresetName($name, $default = null, $disabled = false, $label = null, $help = null)
	{
		return self::get(PresetName::getType(), $name, $default, false, $disabled, $label, $help);
	}

    /**
     * @param      $name
     * @param bool $popup
     * @param null $default
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getRemovePreset($name, $popup = false, $default = null, $disabled = false, $label = null, $help = null)
	{
		$input = self::get(Removepreset::getType(), $name, $default, false, $disabled, $label, $help);
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
	public static function getSelect($name, array $options = array(), $default = null, $multiple = false, $disabled = false, $label = null, $help = null)
	{
		$input = self::get(Selectbox::getType(), $name, $default, $multiple, $disabled, $label, $help);
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
	public static function getSelectGroup($name, array $options = array(), $default = null, $multiple = false, $disabled = false, $label = null, $help = null)
	{
		$input = self::get(Selectgroup::getType(), $name, $default, $multiple, $disabled, $label, $help);
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
		$input = self::get(Radio::getType(), $name, $default, false, $disabled, $label, $help);
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
			'type'  => Header::getType(),
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
		return self::get(Custom::getType(), $name, null, false, false, $label, $help);
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
		return self::get(Iblock::getType(), $name, $default, $multiple, $disabled, $label, $help);
	}

    /**
     * @param string $name
     * @param string $default
     * @param bool   $disabled
     * @param string $label
     * @param string $help
     * @return array
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getLabel($name = '', $default = '', $disabled = false, $label = '', $help = '')
	{
		return array(
		    'name'      => $name ? : Label::getType(),
			'type'      => Label::getType(),
			'label'     => $label,
			'default'   => $default,
            'disabled'  => $disabled,
            'help'      => $help
        );
	}

    /**
     * @param        $label
     * @param string $help
     * @param string $default
     * @return array
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function getLabelShort($label, $help = '', $default = '')
    {
        return array(
            'type'      => Label::getType(),
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
        return self::get(Clock::getType(), $name, $default, false, $disabled, $label, $help);
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

		$submit = self::get(Submit::getType(), $name, $default, false, $disabled, $label, $help);
		$submit['popup'] = $popup;

		return $submit;
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
	public static function getAddPreset($name, $default, $popup = false, $disabled = false, $label = null, $help = null)
	{
		$result = self::get(Addpreset::getType(), $name, $default, false, $disabled, $label, $help);

		$default = trim($default);
		if (!strlen($default))
			throw new ArgumentNullException('default');

		$result['id']       = $name;
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
            'type'      => Hidden::getType(),
            'name'      => $name,
            'default'   => $default
        );
    }
}