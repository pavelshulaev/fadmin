<?php

namespace Rover\Fadmin\Inputs;

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\Config\Option;
use Rover\Fadmin\Inputs\Params\Common;
use Rover\Fadmin\Options;

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
     * @param array $params
     * @param Options $options
     * @param Input|null $parent
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentOutOfRangeException
     * @throws Main\SystemException
     */
    public function __construct(array $params, Options $options, Input $parent = null)
    {
        if (empty($params['name'])) {
            throw new Main\ArgumentNullException('name');
        }

        if (preg_match('#[.]#usi', $params['name'])) {
            throw new Main\ArgumentOutOfRangeException('name');
        }

        if (empty($params['label'])) {
            throw new Main\SystemException("Argument 'label' is null or empty (name '" . $params['name'] . "')");
        }

        if (empty($params['id'])) {
            $params['id'] = $params['name'];
        }

        $this->optionsEngine = $options;
        $this->id            = htmlspecialcharsbx($params['id']);
        $this->name          = htmlspecialcharsbx($params['name']);

        if ($parent instanceof Input) {
            $this->setParent($parent);
        }

        $this->setLabel($params['label']);

        if (array_key_exists('default', $params)) {
            $this->setDefault($params['default']);
        }

        if (isset($params['presetId'])) {
            $this->setPresetId($params['presetId']);
        } elseif (isset($this->parent) && ($this->parent->isPreset())) {
            $this->setPresetId($this->parent->getPresetId());
        }

        if (isset($params['siteId'])) {
            $this->setSiteId($params['siteId']);
        } elseif (isset($this->parent) && $this->parent->getSiteId()) {
            $this->setSiteId($this->parent->getSiteId());
        }

        if (isset($params['multiple'])) {
            $this->setMultiple($params['multiple']);
        }

        if (isset($params['disabled'])) {
            $this->setDisabled($params['disabled']);
        }

        if (isset($params['help'])) {
            $this->setHelp($params['help']);
        }

        if (isset($params['sort']) && intval($params['sort'])) {
            $this->setSort($params['sort']);
        }

        if (array_key_exists('required', $params)) {
            $this->setRequired($params['required']);
        }

        // @TODO: deprecated flag
        if (array_key_exists('display', $params)) {
            $this->setHidden(!$params['display']);
        }

        if (array_key_exists('hidden', $params)) {
            $this->setHidden($params['hidden']);
        }

        if (isset($params['preInput'])) {
            $this->setPreInput($params['preInput']);
        }

        if (isset($params['postInput'])) {
            $this->setPostInput($params['postInput']);
        }
    }

    /**
     * @param array $params
     * @param Tab $tab
     * @return Input
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
    public static function factory(array $params, Tab $tab): Input
    {
        if ($tab->isPreset()) {
            $params['presetId'] = $tab->getPresetId();
        }

        if ($tab->getSiteId()) {
            $params['siteId'] = $tab->getSiteId();
        }

        return self::build($params, $tab->getOptionsEngine(), $tab);
    }

    /**
     * @param array $params
     * @param Options $options
     * @param Input|null $parent
     * @return Input
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function build(array $params, Options $options, Input $parent = null): Input
    {
        $className = __NAMESPACE__ . '\\' . ucfirst($params['type']);

        if (!class_exists($className)) {
            throw new Main\SystemException('Class "' . $className . '" not found!');
        }

        if ($className == __CLASS__) {
            throw new Main\SystemException('Can\'t create "' . $className . '" instance');
        }

        $input = new $className($params, $options, $parent);

        if ($input instanceof Input === false) {
            throw new Main\SystemException('"' . $className . '" is not a child of "' . __CLASS__ . '"');
        }

        return $input;
    }

    /**
     * @param $value
     * @return $this
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setValue($value): static
    {
        if ($this->disabled) {
            throw new Main\SystemException('input is disabled');
        }

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
    private function saveValue($value): bool
    {
        if (false === static::beforeSaveValue($value)) {
            return false;
        }

        Option::set($this->getModuleId(), $this->getOptionName(),
            $value, $this->getSiteId());

        // remove old format value
        if ($this->getFieldName() != $this->getOptionName()) {
            Option::delete($this->getModuleId(), [
                'name'    => $this->getFieldName(),
                'site_id' => $this->getSiteId(),
            ]);
        }

        return true;
    }

    /**
     * @return string
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getOptionName(): string
    {
        return self::getFullPath($this->name, $this->getPresetId());
    }

    /**
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated
     */
    public function removeValue(): void
    {
        $this->clear();
    }

    /**
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function clear(): void
    {
        $this->value = null;

        Option::delete($this->getModuleId(), [
            'name'    => $this->getOptionName(),
            'site_id' => $this->getSiteId()
        ]);

        // old format
        if ($this->getFieldName() != $this->getOptionName()) {
            Option::delete($this->getModuleId(), [
                'name'    => $this->getFieldName(),
                'site_id' => $this->getSiteId()
            ]);
        }
    }

    /**
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function loadValue(): void
    {
        if (false === static::beforeLoadValue()) {
            $this->value = null;
        } else {
            if ($this->getFieldName() == $this->getOptionName()) {
                $this->value = Option::get(
                    $this->getModuleId(),
                    $this->getOptionName(),
                    $this->getDefault(),
                    $this->getSiteId()
                );
            } else {
                // trying to load from new format
                $this->value = Option::get(
                    $this->getModuleId(),
                    $this->getOptionName(),
                    "~value_not_found",
                    $this->getSiteId()
                );

                // trying to load from old format
                if ($this->value == '~value_not_found') {
                    $this->value = Option::get(
                        $this->getModuleId(),
                        $this->getFieldName(),
                        $this->getDefault(),
                        $this->getSiteId()
                    );
                }
            }
        }

        if ($this->multiple) {
            if (!is_array($this->value)) {
                $this->value = unserialize($this->value);
            }

            if (empty($this->value)) {
                $this->value = [];
            }
        }

        static::afterLoadValue($this->value);
    }

    /**
     * @param array $params
     * @param string $moduleId
     * @param string|null $presetId
     * @param string|null $siteId
     * @return string
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getValueStatic(array $params, string $moduleId, string $presetId = null,
        string $siteId = null): string
    {
        if (!isset($params['name'])) {
            throw new Main\ArgumentNullException('name');
        }

        $moduleId = trim($moduleId);
        if (!strlen($moduleId)) {
            throw new Main\ArgumentNullException('moduleId');
        }

        if (!isset($params['default'])) {
            $params['default'] = null;
        }

        $formName   = self::getFullPath($params['name'], $presetId, $siteId);
        $optionName = self::getFullPath($params['name'], $presetId);

        if ($formName == $optionName) {
            return Option::get(
                $moduleId,
                self::getFullPath($params['name'], $presetId),
                $params['default'],
                $siteId);
        }

        // search value in formats...
        $value = Option::get(
            $moduleId,
            self::getFullPath($params['name'], $presetId),
            '~value_not_found',
            $siteId);

        if ($value != '~value_not_found') {
            return $value;
        }

        // old format
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
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getValue(bool $reload = false)
    {
        if (empty($this->value) || $reload) {
            $this->loadValue();
        }

        if (false === static::beforeGetValue($this->value)) {
            $this->value = null;
        }

        return $this->value;
    }

    /**
     * @return bool
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setValueFromRequest(): bool
    {
        if ($this->isDisabled()) {
            return false;
        }

        $request = Application::getInstance()
            ->getContext()
            ->getRequest();

        if (!$request->offsetExists($this->getFieldName())
            && (static::getType() != Checkbox::getType())
            && (static::getType() != Row::getType())
            && (static::getType() != File::getType())) {
            return false;
        }

        $value = $request->get($this->getFieldName());

        if (false === static::beforeSaveRequest($value)) {
            return false;
        }

        if ($this->multiple && is_array($value)) {
            $value = serialize($value);
        }

        $this->setValue($value);

        return true;
    }

    /**
     * @return string
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getClassName(): string
    {
        return get_called_class();
    }

    /**
     * @return mixed
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public static function getType(): string
    {
        $className = static::getClassName();

        return strtolower(substr($className, strrpos($className, '\\') + 1));
    }

    /**
     * @return string
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated use getFieldId()
     */
    public function getValueId(): string
    {
        return $this->getFieldId();
    }

    /**
     * @return string
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getFieldId(): string
    {
        return self::getFullPath($this->getId(), $this->getPresetId(), $this->getSiteId());
    }

    /**
     * @return string
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated use getFieldName()
     */
    public function getValueName(): string
    {
        return $this->getFieldName();
    }

    /**
     * @return string
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated use getFieldName()
     */
    public function getFormName(): string
    {
        return $this->getFieldName();
    }

    /**
     * @return string
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getFieldName(): string
    {
        return self::getFullPath($this->name, $this->getPresetId(), $this->getSiteId());
    }

    /**
     * @param        $value
     * @param string|null $presetId
     * @param string|null $siteId
     * @return string
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getFullPath($value, string $presetId = null, string $siteId = null): string
    {
        $value = trim($value);
        if (!strlen($value)) {
            throw new Main\ArgumentNullException('value');
        }

        $result = $value;

        if ($presetId) {
            $result = htmlspecialcharsbx($presetId) . self::SEPARATOR . $result;
        }

        if ($siteId) {
            $result = htmlspecialcharsbx($siteId) . self::SEPARATOR . $result;
        }

        return $result;
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function __clone()
    {
        $children = $this->getChildren();
        if (empty($children)) {
            return;
        }

        $newChildren = [];
        $childrenCnt = count($children);

        for ($i = 0; $i < $childrenCnt; ++$i) {
            /** @var Input $input */
            $child         = $children[$i];
            $newChildren[] = clone $child;
        }

        $this->children = $newChildren;
    }

    /**
     * @param array $filter
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function checkByFilter(array $filter): bool
    {
        $found = true;

        if (isset($filter['id']) && strlen($filter['id'])) {
            $found = ($filter['id'] == $this->getId());
        }

        if (isset($filter['name']) && strlen($filter['name'])) {
            $found = $found && ($filter['name'] == $this->getName());
        }

        if (isset($filter['siteId']) && strlen($filter['siteId'])) {
            $found = $found && ($filter['siteId'] == $this->getSiteId());
        }

        if (isset($filter['presetId']) && strlen($filter['presetId'])) {
            $found = $found && ($filter['presetId'] == $this->getPresetId());
        }

        return $found;
    }

    /**
     * @param $filter
     * @return array
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function searchByFilter($filter): array
    {
        $children    = static::getChildren();
        $childrenCnt = count($children);
        $result      = [];

        for ($i = 0; $i < $childrenCnt; ++$i) {
            $child = $children[$i];
            if (!$child instanceof self) {
                continue;
            }

            if ($child->checkByFilter($filter)) {
                $result[] = $child;
            }

            $childrenLevel2 = $child->getChildren();
            if (!count($childrenLevel2)) {
                continue;
            }

            $childResult = $child->searchByFilter($filter);
            if (count($childResult)) {
                $result = array_merge($result, $childResult);
            }
        }

        return $result;
    }

    /**
     * @param      $name
     * @param null $presetId
     * @param null $siteId
     * @return Input|null
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function searchOneByName($name, $presetId = null, $siteId = null): ?Input
    {
        $name = trim($name);
        if (!strlen($name)) {
            throw new Main\ArgumentNullException('name');
        }

        $filter = [
            'name'     => $name,
            'siteId'   => $siteId ?? $this->getSiteId(),
            'presetId' => $presetId ?? $this->getPresetId()
        ];

        // allows to make search in tabcontrol also
        $searchResult = static::searchByFilter($filter);

        if (count($searchResult) == 1) {
            return reset($searchResult);
        }

        if (!count($searchResult)) {
            return null;
        }

        throw new Main\ArgumentOutOfRangeException('name');
    }

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeSaveRequest(&$value): bool
    {
        return true;
    }

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeGetValue(&$value): bool
    {
        return true;
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeLoadValue(): bool
    {
        return true;
    }

    /**
     * @param $value
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function afterLoadValue(&$value)
    {
    }

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeSaveValue(&$value): bool
    {
        return true;
    }
}