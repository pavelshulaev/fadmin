<?php
namespace Rover\Fadmin\Inputs;

use Bitrix\Main;
use Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;
use Rover\Fadmin\Inputs\Params\Common;
use \Rover\Fadmin\Options;

/**
 * Class Input
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
abstract class Input
{
    use Common;

    /** @deprecated  */
    const TYPE__ADD_PRESET      = 'addpreset';
    /** @deprecated  */
    const TYPE__CHECKBOX        = 'checkbox';
    /** @deprecated  */
    const TYPE__CLOCK           = 'clock';
    /** @deprecated  */
	const TYPE__COLOR           = 'color';
    /** @deprecated  */
    const TYPE__CUSTOM          = 'custom';
    /** @deprecated  */
    const TYPE__DATE            = 'date';
    /** @deprecated  */
	const TYPE__DATETIME        = 'datetime';
    /** @deprecated  */
    const TYPE__FILE            = 'file';
    /** @deprecated  */
	const TYPE__HEADER          = 'header';
    /** @deprecated  */
    const TYPE__HIDDEN          = 'hidden';
    /** @deprecated  */
    const TYPE__IBLOCK          = 'iblock';
    /** @deprecated  */
    const TYPE__LABEL           = 'label';
    /** @deprecated  */
    const TYPE__NUMBER          = 'number';
    /** @deprecated  */
    const TYPE__PRESET_NAME     = 'presetname';
    /** @deprecated  */
    const TYPE__RADIO           = 'radio';
    /** @deprecated  */
    const TYPE__REMOVE_PRESET   = 'removepreset';
    /** @deprecated  */
    const TYPE__SELECTBOX       = 'selectbox';
    /** @deprecated  */
    const TYPE__SELECT_GROUP    = 'selectgroup';
    /** @deprecated  */
    const TYPE__SCHEDULE        = 'schedule';
    /** @deprecated  */
    const TYPE__SUBMIT          = 'submit';
    /** @deprecated  */
    const TYPE__SUBTABCONTROL   = 'subtabcontrol';
    /** @deprecated  */
    const TYPE__SUBTAB          = 'subtab';
    /** @deprecated  */
    const TYPE__TEXT            = 'text';
    /** @deprecated  */
	const TYPE__TEXTAREA        = 'textarea';

    const SEPARATOR = '__';

    /**
     * Input constructor.
     *
     * @param array      $params
     * @param Options    $options
     * @param Input|null $parent
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentOutOfRangeException
     */
	public function __construct(array $params, Options $options, Input $parent = null)
	{
		if (is_null($params['name']))
			throw new Main\ArgumentNullException('name');

		if (preg_match('#[.]#usi', $params['name']))
		    throw new Main\ArgumentOutOfRangeException('name');

		if (is_null($params['label']))
			throw new Main\ArgumentNullException('label');

		if (is_null($params['id']))
			$params['id'] = $params['name'];

		$this->optionsEngine= $options;
		$this->id           = htmlspecialcharsbx($params['id']);
		$this->name         = htmlspecialcharsbx($params['name']);

		if ($parent instanceof Input)
		    $this->setParent($parent);

		$this->setLabel($params['label']);
		$this->setDefault($params['default']);

        if (isset($params['presetId']))
            $this->presetId = $params['presetId'];

        if (isset($params['siteId']))
            $this->siteId = $params['siteId'];

		if (isset($params['multiple']))
			$this->multiple = (bool)$params['multiple'];

		if (isset($params['disabled']))
			$this->disabled = (bool)$params['disabled'];

		if (isset($params['help']))
			$this->help = $params['help'];

		if (isset($params['sort']) && intval($params['sort']))
			$this->sort = intval($params['sort']);

        if (array_key_exists('hidden', $params))
            $this->hidden = (bool)$params['hidden'];

		// @TODO: deprecated
		if (array_key_exists('display', $params))
			$this->hidden = !(bool)$params['display'];
	}

    /**
     * @param array $params
     * @param Tab   $tab
     * @return mixed
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function factory(array $params, Tab $tab)
	{
		return self::build($params, $tab->getOptionsEngine(), $tab);
	}

    /**
     * @param array      $params
     * @param Options    $options
     * @param Input|null $parent
     * @return mixed
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function build(array $params, Options $options, Input $parent = null)
    {
        $className = '\Rover\Fadmin\Inputs\\' . ucfirst($params['type']);

        if (!class_exists($className))
            throw new Main\SystemException('Class "' . $className . '" not found!');

        if ($className == '\Rover\Fadmin\Inputs\Input')
            throw new Main\SystemException('Can\'t create "' . $className . '" instance');

        $input = new $className($params, $options, $parent);

        if ($input instanceof Input === false)
            throw new Main\SystemException('"' . $className . '" is not "\Rover\Fadmin\Inputs\Input" instance');

        return $input;
    }

    /**
     * @param $value
     * @return $this
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setValue($value)
    {
        if ($this->disabled)
            throw new Main\SystemException('input is disabled');

        $this->value = $this->saveValue($value) ? $value : null;

        return $this;
    }

    /**
     * @param $value
     * @return bool
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    private function saveValue($value)
    {
        if (!static::beforeSaveValue($value))
            return false;

        Option::set(
            $this->getModuleId(),
            $this->getValueName(),
            $value,
            $this->getSiteId()
        );

        return true;
    }

    /**
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated
     */
    public function removeValue()
    {
        $this->clear();
    }

    /**
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function clear()
    {
        $this->value = null;
        $filter      = array(
            'name'      => $this->getValueName(),
            'site_id'   => $this->getSiteId()
        );

        Option::delete($this->getModuleId(), $filter);
    }

    /**
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function loadValue()
    {
        $this->value = (false === static::beforeLoadValue())
            ? null
            : Option::get($this->getModuleId(), $this->getValueName(),
                $this->getDefault(), $this->getSiteId());

        if ($this->multiple) {
            if (!is_array($this->value))
                $this->value = unserialize($this->value);

            if (empty($this->value))
                $this->value = array();
        }

        static::afterLoadValue($this->value);
    }

    /**
     * @param array  $params
     * @param        $moduleId
     * @param string $presetId
     * @param string $siteId
     * @return string
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getValueStatic(array $params, $moduleId, $presetId = '', $siteId = '')
    {
        if (!isset($params['name']))
            throw new Main\ArgumentNullException('name');

        $moduleId = trim($moduleId);
        if (!strlen($moduleId))
            throw new Main\ArgumentNullException('moduleId');

        if (!isset($params['default']))
            $params['default'] = null;

        return Option::get(
            $moduleId,
            self::getFullPath($params['name'], $presetId, $siteId),
            $params['default'],
            $siteId);
    }

    /**
     * @param bool $reload
     * @return array|string
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getValue($reload = false)
    {
        if (empty($this->value) || $reload)
            $this->loadValue();

        if (!static::beforeGetValue($this->value))
            $this->value = null;

        return $this->value;
    }

    /**
     * @return bool
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function setValueFromRequest()
	{
	    if ($this->isDisabled())
	        return false;

        $request = Application::getInstance()
            ->getContext()
            ->getRequest();

		if (!$request->offsetExists($this->getValueName())
			&& (static::getType() != Checkbox::getType())
            && (static::getType() != File::getType()))
			return false;

        $value = $request->get($this->getValueName());

        if (!static::beforeSaveRequest($value))
            return false;

        if ($this->multiple && is_array($value))
            $value = serialize($value);

        $this->setValue($value);

        return true;
    }

    /**
     * @return string
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getClassName()
    {
        return get_called_class();
    }

    /**
     * @return mixed
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public static function getType()
    {
        $className = static::getClassName();

        return strtolower(substr($className, strrpos($className, '\\') + 1));
    }

    /**
     * @return string
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getValueId()
    {
        return self::getFullPath($this->id, $this->getPresetId(), $this->getSiteId());
    }

    /**
     * @return string
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getValueName()
    {
        return self::getFullPath($this->name, $this->getPresetId(), $this->getSiteId());
    }

    /**
     * @param        $value
     * @param string $presetId
     * @param string $siteId
     * @return string
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getFullPath($value, $presetId = '', $siteId = '')
    {
        $value = trim($value);
        if (!strlen($value))
            throw new Main\ArgumentNullException('value');

        $result = $value;

        if (strlen($presetId))
            $result = htmlspecialcharsbx($presetId) . self::SEPARATOR . $result;

        if (strlen($siteId))
            $result = htmlspecialcharsbx($siteId) . self::SEPARATOR . $result;

        return $result;
    }

    /**
     * @param $value
     * @return mixed
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeSaveRequest(&$value)
    {
        return true;
    }

    /**
     * @param $value
     * @return mixed
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeGetValue(&$value)
    {
        return true;
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeLoadValue() {}

    /**
     * @param $value
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function afterLoadValue(&$value) {}

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeSaveValue(&$value)
    {
        return true;
    }

    /**
     * @return null|Input
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getTab()
    {
        $input = $this;
        do {
            $input = $input->getParent();
        } while (!is_null($input) && ($input->getClassName() != Tab::getClassName()));

        return $input;
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function __clone()
    {
        $children       = $this->getChildren();
        if (is_null($children))
            return;

        $newChildren    = array();
        $childrenCnt    = count($children);

        for ($i = 0; $i < $childrenCnt; ++$i) {
            /** @var Input $input */
            $child          = $children[$i];
            $newChildren[]  = clone $child;
        }

        $this->children = $newChildren;
    }
}