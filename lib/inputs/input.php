<?php
namespace Rover\Fadmin\Inputs;

use Bitrix\Main;
use Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;
use Rover\Fadmin\Inputs\Params\Common;
use \Rover\Fadmin\Tab;
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

    const TYPE__ADD_PRESET      = 'addpreset';
	const TYPE__CHECKBOX        = 'checkbox';
    const TYPE__CLOCK           = 'clock';
	const TYPE__COLOR           = 'color';
    const TYPE__CUSTOM          = 'custom';
    const TYPE__DATE            = 'date';
	const TYPE__DATETIME        = 'datetime';
    const TYPE__FILE            = 'file';
	const TYPE__HEADER          = 'header';
    const TYPE__HIDDEN          = 'hidden';
    const TYPE__IBLOCK          = 'iblock';
    const TYPE__LABEL           = 'label';
    const TYPE__NUMBER          = 'number';
    const TYPE__PRESET_NAME     = 'presetname';
    const TYPE__RADIO           = 'radio';
    const TYPE__REMOVE_PRESET   = 'removepreset';
    const TYPE__SELECTBOX       = 'selectbox';
    const TYPE__SELECT_GROUP    = 'selectgroup';
    const TYPE__SCHEDULE        = 'schedule';
    const TYPE__SUBMIT          = 'submit';
    const TYPE__TEXT            = 'text';
	const TYPE__TEXTAREA        = 'textarea';

    const SEPARATOR = '__';

    /**
     * @var Tab
     * @deprecated
     */
	protected $tab;

	/** @var Options */
	protected $optionsEngine;

    /**
     * Input constructor.
     *
     * @param Options $options
     * @param array   $params
     * @param Tab     $tab
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentOutOfRangeException
     */
	public function __construct(Options $options, array $params, Tab $tab)
	{
		if (is_null($params['name']))
			throw new Main\ArgumentNullException('name');

		if (preg_match('#[.]#usi', $params['name']))
		    throw new Main\ArgumentOutOfRangeException('name');

		if (is_null($params['label']))
			throw new Main\ArgumentNullException('label');

		if (is_null($params['id']))
			$params['id'] = $params['name'];

		$this->options = $options;

		// @TODO: delete
		$this->tab = $tab;

		$this->id   = htmlspecialcharsbx($params['id']);
		$this->name = htmlspecialcharsbx($params['name']);

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
     * @param Options $options
     * @param array   $params
     * @param Tab     $tab
     * @return mixed
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public static function factory(Options $options, array $params, Tab $tab)
	{
		$className = '\Rover\Fadmin\Inputs\\' . ucfirst($params['type']);

		if (!class_exists($className))
			throw new Main\SystemException('Class "' . $className . '" not found!');

		if ($className == '\Rover\Fadmin\Inputs\Input')
			throw new Main\SystemException('Can\'t create "' . $className . '" instance');

		// @todo: remove tab
		$input = new $className($options, $params, $tab);

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

        $this->value = $this->saveValue($value)
            ? $value
            : null;

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
     */
    public function removeValue()
    {
        $this->value = null;
        $filter      = array(
            'name'      => $this->getValueName(),
            'site_id'   => $this->getSiteId()
        );

        Option::delete($this->getModuleId(), $filter);
    }

    /**
     * @return Options
     * @author Pavel Shulaev (http://rover-it.me)
     */
    protected function getOptionsEngine()
    {
        return $this->optionsEngine;
    }

    /**
     * @return mixed
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getModuleId()
    {
        return $this->optionsEngine->getModuleId();
    }

    /**
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function loadValue()
    {
        $this->value = Option::get(
            $this->getModuleId(),
            $this->getValueName(),
            $this->default,
            $this->getSiteId()
        );

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
            return null;

        return $this->value;
    }

    /**
     * @return bool
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setValueFromRequest()
    {
        if ($this->getDisabled())
            return false;

        $request = Application::getInstance()
            ->getContext()
            ->getRequest();

        if ((!$request->offsetExists($this->getValueName())
            && ($this->getType() != self::TYPE__CHECKBOX)))
            return false;

        $value = $request->get($this->getValueName());

        // EVENT: beforeSaveRequest
        if (!static::beforeSaveRequest($value))
            return false;

        //serialize multiple value
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
    public function getType()
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
     * @param Tab $tab
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated
     */
    public function setTab(Tab $tab)
    {
        $this->tab = $tab;

        return $this;
    }

    /**
     * @return Tab
     * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated
     */
    public function getTab()
    {
        return $this->tab;
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
}