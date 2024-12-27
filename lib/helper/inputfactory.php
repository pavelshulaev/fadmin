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
use Rover\Fadmin\Inputs\Date;
use Rover\Fadmin\Inputs\DateTime;
use Rover\Fadmin\Inputs\Header;
use Rover\Fadmin\Inputs\Hidden;
use Rover\Fadmin\Inputs\Iblock;
use Rover\Fadmin\Inputs\Label;
use Rover\Fadmin\Inputs\Number;
use Rover\Fadmin\Inputs\Password;
use Rover\Fadmin\Inputs\PresetName;
use Rover\Fadmin\Inputs\Radio;
use Rover\Fadmin\Inputs\Removepreset;
use Rover\Fadmin\Inputs\Selectbox;
use Rover\Fadmin\Inputs\Selectgroup;
use Rover\Fadmin\Inputs\Submit;
use Rover\Fadmin\Inputs\SubTab;
use Rover\Fadmin\Inputs\SubTabControl;
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
    protected static function addFields(array $input): array
    {
        if (!isset($input['type'])) {
            throw new ArgumentNullException('type');
        }

        if (!isset($input['name'])) {
            throw new ArgumentNullException('name');
        }

        if (empty($input['label'])) {
            $input['label'] = Loc::getMessage($input['name'] . '_label');
        }

        if (empty($input['help'])) {
            $input['help'] = Loc::getMessage($input['name'] . '_help');
        }

        if (empty($input['default'])) {
            $input['default'] = Loc::getMessage($input['name'] . '_default');
        }

        if (empty($input['multiple'])) {
            $input['multiple'] = false;
        }

        if (empty($input['disabled'])) {
            $input['disabled'] = false;
        }

        return $input;
    }

    /**
     * @param string $type
     * @param string $name
     * @param null $default
     * @param bool $multiple
     * @param bool $disabled
     * @param string|null $label
     * @param string|null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function get(string $type, string $name, $default = null, bool $multiple = false, bool $disabled = false,
        string $label = null, string $help = null, string $siteId = ''): array
    {
        if (!isset($type)) {
            throw new ArgumentNullException('type');
        }

        if (!isset($name)) {
            throw new ArgumentNullException('name');
        }

        return self::addFields([
            'type'     => $type,
            'name'     => $name,
            'default'  => $default,
            'multiple' => $multiple,
            'disabled' => $disabled,
            'label'    => $label,
            'help'     => $help,
            'siteId'   => $siteId
        ]);
    }

    /**
     * @param        $name
     * @param string $default
     * @param null $label
     * @param bool $disabled
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getText($name, string $default = '', bool $disabled = false, $label = null, $help = null, $siteId = ''): array
    {
        return self::get(Text::getType(), $name, $default, false, $disabled, $label, $help, $siteId);
    }

    /**
     * @param        $name
     * @param string $default
     * @param null $label
     * @param bool $disabled
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getPassword($name, string $default = '', bool $disabled = false, $label = null, $help = null,
        string $siteId = ''): array
    {
        return self::get(Password::getType(), $name, $default, false, $disabled, $label, $help, $siteId);
    }

    /**
     * @param        $name
     * @param null $default
     * @param null $cols
     * @param null $rows
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @param string $siteId
     * @param bool $htmlEditor
     * @param bool $htmlEditorBB
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getTextarea($name, $default = null, $cols = null, $rows = null, bool $disabled = false,
        $label = null, $help = null, string $siteId = '', bool $htmlEditor = false, bool $htmlEditorBB = false): array
    {
        $textarea = self::get(Textarea::getType(), $name, $default, false, $disabled, $label, $help, $siteId);

        $cols = intval($cols);
        $rows = intval($rows);

        if ($cols) {
            $textarea['cols'] = $cols;
        }
        if ($rows) {
            $textarea['rows'] = $rows;
        }
        if ($htmlEditor) {
            $textarea['htmlEditor'] = true;
        }
        if ($htmlEditorBB) {
            $textarea['htmlEditorBB'] = true;
        }

        return $textarea;
    }

    /**
     * @param      $name
     * @param null $default
     * @param null $label
     * @param bool $disabled
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getNumber($name, $default = null, bool $disabled = false, $label = null, $help = null,
        string $siteId = ''): array
    {
        return self::get(Number::getType(), $name, $default, false, $disabled, $label, $help, $siteId);
    }

    /**
     * @param        $name
     * @param string $default
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getCheckbox($name, string $default = 'Y', bool $disabled = false, $label = null, $help = null,
        string $siteId = ''): array
    {
        return self::get(Checkbox::getType(), $name, $default == 'Y' ? 'Y' : 'N', false, $disabled, $label, $help,
            $siteId);
    }

    /**
     * @param      $name
     * @param null $default
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getPresetName($name, $default = null, $disabled = false, $label = null, $help = null,
        $siteId = '')
    {
        return self::get(PresetName::getType(), $name, $default, false, $disabled, $label, $help, $siteId);
    }

    /**
     * @param      $name
     * @param bool $popup
     * @param null $default
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getRemovePreset($name, $popup = false, $default = null, $disabled = false, $label = null,
        $help = null, $siteId = '')
    {
        $input = self::get(Removepreset::getType(), $name, $default, false, $disabled, $label, $help, $siteId);
        if ($popup !== false) {
            $input['popup'] = $popup;
        }

        return $input;
    }

    /**
     * @param       $name
     * @param array $options
     * @param null $default
     * @param bool $multiple
     * @param null $label
     * @param bool $disabled
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getSelect($name, array $options = [], $default = null, $multiple = false, $disabled = false,
        $label = null, $help = null, $siteId = '')
    {
        $input            =
            self::get(Selectbox::getType(), $name, $default, $multiple, $disabled, $label, $help, $siteId);
        $input['options'] = $options;

        return $input;
    }

    /**
     * @param       $name
     * @param array $options
     * @param null $default
     * @param bool $multiple
     * @param null $label
     * @param bool $disabled
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getSelectGroup($name, array $options = [], $default = null, $multiple = false,
        $disabled = false, $label = null, $help = null, $siteId = '')
    {
        $input            =
            self::get(Selectgroup::getType(), $name, $default, $multiple, $disabled, $label, $help, $siteId);
        $input['options'] = $options;

        return $input;
    }

    /**
     * @param       $name
     * @param array $inputs
     * @param null $default
     * @param bool $multiple
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getSubTab($name, array $inputs = [], $default = null, $multiple = false, $disabled = false,
        $label = null, $help = null, $siteId = '')
    {
        $input           = self::get(SubTab::getType(), $name, $default, $multiple, $disabled, $label, $help, $siteId);
        $input['inputs'] = $inputs;

        return $input;
    }

    /**
     * @param        $name
     * @param array $subTabs
     * @param null $default
     * @param bool $multiple
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getSubTabControl($name, array $subTabs = [], $default = null, $multiple = false,
        $disabled = false, $label = null, $help = null, $siteId = '')
    {
        $input            =
            self::get(SubTabControl::getType(), $name, $default, $multiple, $disabled, $label, $help, $siteId);
        $input['subTabs'] = $subTabs;

        return $input;
    }

    /**
     * @param       $name
     * @param array $options
     * @param null $default
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getRadio($name, array $options = [], $default = null, $disabled = false, $label = null,
        $help = null, $siteId = '')
    {
        $input            = self::get(Radio::getType(), $name, $default, false, $disabled, $label, $help, $siteId);
        $input['options'] = $options;

        return $input;
    }

    /**
     * @param $label
     * @param string $siteId
     * @return array
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public static function getHeader($label, $siteId = '')
    {
        return [
            'type'   => Header::getType(),
            'label'  => $label,
            'siteId' => $siteId
        ];
    }

    /**
     * @param      $name
     * @param null $label
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getCustom($name, $label = null, $help = null, $siteId = '')
    {
        return self::get(Custom::getType(), $name, null, false, false, $label, $help, $siteId);
    }

    /**
     * @param      $name
     * @param bool $multiple
     * @param null $default
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getIblock($name, $multiple = false, $default = null, $disabled = false, $label = null,
        $help = null, $siteId = '')
    {
        return self::get(Iblock::getType(), $name, $default, $multiple, $disabled, $label, $help, $siteId);
    }

    /**
     * @param string $name
     * @param string $default
     * @param bool $disabled
     * @param string $label
     * @param string $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getLabel($name = '', $default = '', $disabled = false, $label = '', $help = '', $siteId = '')
    {
        $name = $name ?: Label::getType();

        return self::get(Label::getType(), $name, $default, false, $disabled, $label, $help, $siteId);
    }

    /**
     * @param        $label
     * @param string $help
     * @param string $default
     * @param string $siteId
     * @return array
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getLabelShort($label, $help = '', $default = '', $siteId = '')
    {
        return [
            'type'    => Label::getType(),
            'label'   => $label,
            'default' => $default,
            'help'    => $help,
            'siteId'  => $siteId
        ];
    }

    /**
     * @param        $name
     * @param string $default
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getClock($name, $default = null, $disabled = false, $label = null, $help = null,
        $siteId = '')
    {
        $clock = self::get(Clock::getType(), $name, $default, false, $disabled, $label, $help, $siteId);
        if (empty($clock['default'])) {
            $clock['default'] = '0:00';
        }

        return $clock;
    }

    /**
     * @param      $name
     * @param      $default
     * @param bool $popup
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getSubmit($name, $default = null, $popup = false, $disabled = false, $label = null,
        $help = null, $siteId = '')
    {
        $submit          = self::get(Submit::getType(), $name, $default, false, $disabled, $label, $help, $siteId);
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
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getAddPreset($name, $default, $popup = false, $disabled = false, $label = null, $help = null,
        $siteId = '')
    {
        $result = self::get(Addpreset::getType(), $name, $default, false, $disabled, $label, $help, $siteId);

        $default = trim($result['default']);
        if (!strlen($default)) {
            throw new ArgumentNullException('default');
        }

        $result['id']    = $name;
        $result['popup'] = $popup;

        return $result;
    }

    /**
     * @param        $name
     * @param string $default
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public static function getHidden($name, $default = '', $siteId = '')
    {
        $name = trim($name);
        if (!$name) {
            throw new ArgumentNullException('name');
        }

        return [
            'type'    => Hidden::getType(),
            'name'    => $name,
            'default' => $default,
            'siteId'  => $siteId
        ];
    }

    /**
     * @param        $name
     * @param string $default
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getDateTime($name, $default = '', $disabled = false, $label = null, $help = null,
        $siteId = '')
    {
        return self::get(DateTime::getType(), $name, $default, false, $disabled, $label, $help, $siteId);
    }

    /**
     * @param        $name
     * @param string $default
     * @param bool $disabled
     * @param null $label
     * @param null $help
     * @param string $siteId
     * @return array
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getDate($name, string $default = '', bool $disabled = false, $label = null, $help = null, string $siteId = ''): array
    {
        return self::get(Date::getType(), $name, $default, false, $disabled, $label, $help, $siteId);
    }
}