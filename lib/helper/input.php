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
/**
 * Class Input
 *
 * @package Rover\Fadmin\Helper
 * @author  Pavel Shulaev (http://rover-it.me)
 * @deprecated use InputFactory
 */
class Input
{
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
     * @deprecated
     */
	public static function get($type, $name, $default = null, $multiple = false, $disabled = false, $label = null, $help = null)
	{
	    return InputFactory::get($type, $name, $default, $multiple, $disabled, $label, $help);
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
     * @deprecated
     */
	public static function getText($name, $default = '', $label = null, $disabled = false, $help = null)
	{
	    return InputFactory::getText($name, $default, $disabled, $label, $help);
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
     * @deprecated
     */
	public static function getTextarea($name, $default = '', $cols = null, $rows = null, $disabled = false, $label = null, $help = null)
	{
	    return InputFactory::getTextarea($name, $default, $cols, $rows, $disabled, $label, $help);
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
     * @deprecated
     */
	public static function getNumber($name, $default = null, $label = null, $disabled = false, $help = null)
	{
	    return InputFactory::getNumber($name, $default, $disabled, $label, $help);
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
     * @deprecated
     */
	public static function getCheckbox($name, $default = 'Y', $disabled = false, $label = null, $help = null)
	{
	    return InputFactory::getCheckbox($name, $default, $disabled, $label, $help);
	}

	/**
	 * @param $name
	 * @return array
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated
	 */
	public static function getPresetName($name)
	{
	    return InputFactory::getPresetName($name);
	}

    /**
     * @param      $name
     * @param bool $popup
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public static function getRemovePreset($name, $popup = false)
	{
	    return InputFactory::getRemovePreset($name, $popup);
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
     * @deprecated
     */
	public static function getSelect($name, array $options = array(), $default = null, $multiple = false, $label = null, $disabled = false, $help = null)
	{
	    return InputFactory::getSelect($name, $options, $default, $multiple, $disabled, $label, $help);
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
     * @deprecated
     */
	public static function getSelectGroup($name, array $options = array(), $default = null, $multiple = false, $label = null, $disabled = false, $help = null)
	{
	    return InputFactory::getSelectGroup($name, $options, $default, $multiple, $disabled, $label, $help);
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
     * @deprecated
     */
	public static function getRadio($name, array $options = array(), $default = null, $disabled = false, $label = null, $help = null)
	{
	    return InputFactory::getRadio($name, $options, $default, $disabled, $label, $help);
	}

	/**
	 * @param $label
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated
	 */
	public static function getHeader($label)
	{
	    return InputFactory::getHeader($label);
	}

    /**
     * @param      $name
     * @param null $label
     * @param null $help
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public static function getCustom($name, $label = null, $help = null)
	{
	    return InputFactory::getCustom($name, $label, $help);
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
     * @deprecated
     */
	public static function getIblock($name, $multiple = false, $default = null, $disabled = false, $label = null, $help = null)
	{
	    return InputFactory::getIblock($name, $multiple, $default, $disabled, $label, $help);
	}

    /**
     * @param        $label
     * @param string $default
     * @param string $help
     * @return array
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public static function getLabel($label, $default = '', $help = '')
	{
	    return InputFactory::getLabelShort($label, $help, $default);
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
     * @deprecated
     */
	public static function getClock($name, $default = '0:00', $disabled = false, $label = null, $help = null)
    {
        return InputFactory::getClock($name, $default, $disabled, $label, $help);
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
     * @deprecated
     */
	public static function getSubmit($name, $default, $popup = false, $disabled = false, $label = null, $help = null)
	{
	    return InputFactory::getSubmit($name, $default, $popup, $disabled, $label, $help);
	}

    /**
     * @param      $name
     * @param      $default
     * @param bool $popup
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public static function getAddPreset($name, $default, $popup = false)
	{
	    return InputFactory::getAddPreset($name, $default, $popup);
	}

    /**
     * @param        $name
     * @param string $default
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated
     */
	public static function getHidden($name, $default = '')
    {
        return InputFactory::getHidden($name, $default);
    }
}