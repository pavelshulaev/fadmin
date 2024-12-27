<?php

namespace Rover\Fadmin\Options;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Inputs\Tab;
use Rover\Fadmin\Options;

/**
 * Class Presets
 * @package Fadmin
 * @author Pavel Shulaev (http://rover-it.me)
 */
class Preset
{
    const OPTION_ID = 'rover-op-presets';

    protected Options $options;

    protected array $presets;

    /** @param Options $options */
    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * @param string|null $siteId
     * @param bool $reload
     * @return mixed
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getList(string $siteId = null, bool $reload = false): mixed
    {
        if (!isset($siteId)) {
            $siteId = '';
        }

        if (!isset($this->presets[$siteId]) || $reload) {
            $presets = unserialize(Option::get($this->options->getModuleId(),
                self::OPTION_ID, '', $siteId));

            if (empty($presets)) {
                $presets = [];
            }

            $this->presets[$siteId] = $presets;
        }

        return $this->presets[$siteId];
    }

    /**
     * @param string|null $siteId
     * @param bool $reload
     * @return array
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getInstancesList(string $siteId = null, bool $reload = false): array
    {
        $presets     = $this->getList($siteId);
        $list        = [];
        $presetClass = $this->options->settings->getPresetClass();

        foreach ($presets as $preset) {
            $presetInstance = $presetClass::getInstance($preset['id'], $this->options, $reload);

            if (!$presetInstance instanceof \Rover\Fadmin\Preset) {
                throw new ArgumentOutOfRangeException('presetInstance');
            }

            $list[$preset['id']] = $presetInstance;
        }

        return $list;
    }

    /**
     * @param string|null $siteId
     * @param bool $reload
     * @return array
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getIds(string $siteId = null, bool $reload = false): array
    {
        return array_keys($this->getList($siteId, $reload));
    }

    /**
     * @param int $id
     * @param string|null $siteId
     * @param bool $reload
     * @return null
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getById(int $id, string $siteId = null, bool $reload = false)
    {
        if (!$id) {
            throw new ArgumentNullException('id');
        }

        $presets = $this->getList($siteId, $reload);

        if (isset($presets[$id])) {
            return $presets[$id];
        }

        return null;
    }

    /**
     * @param string|null $siteId
     * @param bool $reload
     * @return int
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getCount(string $siteId = null, bool $reload = false): int
    {
        return count($this->getList($siteId, $reload));
    }

    /**
     * @param        $value
     * @param string $siteId
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function add($value, string $siteId = ''): bool
    {
        if (!$this->options->event
            ->handle(Event::BEFORE_ADD_PRESET, compact('siteId', 'value'))
            ->isSuccess()) {
            return false;
        }

        $params = $this->options->event->getParameters();

        if (!isset($params['name'])) {
            $params['name'] = $params['value'];
        }

        $name    = trim($params['name']);
        $presets = $this->getList($params['siteId'], true);

        if (!count($presets)) {
            $presets = [];
            $id      = 1;
        } else {
            $id = max(array_keys($presets)) + 1;
        }

        $presets[$id] = [
            'id'   => $id,
            'name' => htmlspecialcharsbx($name)
        ];

        $this->update($presets, $siteId);

        $params = $this->options->event
            ->handle(Event::AFTER_ADD_PRESET, compact('id', 'value'))
            ->getParameters();

        // reload tabs after event!!!
        $this->options->getTabControl()->reloadTabs();

        return $params['id'];
    }

    /**
     * @param int $id
     * @param string $siteId
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function remove(int $id, string $siteId = ''): bool
    {
        if (!$id) {
            throw new ArgumentNullException('id');
        }

        // action beforeRemovePreset
        if (!$this->options->event
            ->handle(Event::BEFORE_REMOVE_PRESET, compact('siteId', 'id'))
            ->isSuccess()) {
            return false;
        }

        $params = $this->options->event->getParameters();
        /** @var Tab $presetTab */
        $presetTab = $this->options->getTabControl()
            ->getTabByPresetId($params['id'], $params['siteId']);

        if ($presetTab instanceof Tab === false) {
            throw new ArgumentOutOfRangeException('tab');
        }

        $presetTab->clear();

        $presets = $this->getList($siteId, true);

        foreach ($presets as $num => $preset) {
            if ($params['id'] == $preset['id']) {
                unset($presets[$num]);
                $this->update($presets, $siteId);
                break;
            }
        }

        // action afterRemovePreset
        $this->options->event->handle(Event::AFTER_REMOVE_PRESET, $params);

        return true;
    }

    /**
     * @param array $presets
     * @param string $siteId
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function update(array $presets, string $siteId = ''): void
    {
        Option::set($this->options->getModuleId(),
            self::OPTION_ID, serialize($presets), $siteId);

        // reset cache
        unset($this->presets);
    }

    /**
     * @param        $sortFunc
     * @param string $siteId
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function sort($sortFunc, string $siteId = ''): void
    {
        $presets = $this->getList($siteId, true);
        usort($presets, $sortFunc);
        $this->update($presets, $siteId);
    }

    /**
     * @param int $id
     * @param string $siteId
     * @param bool $reload
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function isExists(int $id, string $siteId = '', bool $reload = false): bool
    {
        if (!$id) {
            return false;
        }

        return in_array($id, $this->getIds($siteId, $reload));
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $siteId
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function updateName(int $id, string $name, string $siteId = ''): void
    {
        if (!$id) {
            throw new ArgumentNullException('id');
        }

        $name = trim($name);
        if (!$name) {
            throw new ArgumentNullException('name');
        }

        $presets = $this->getList($siteId, true);

        foreach ($presets as &$preset) {
            if ($preset['id'] != $id) {
                continue;
            }

            $preset['name'] = $name;
            break;
        }

        $this->update($presets, $siteId);
    }

    /**
     * @param int $id
     * @param string $siteId
     * @param bool $reload
     * @return null
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getNameById(int $id, string $siteId = '', bool $reload = false)
    {
        $preset = $this->getById($id, $siteId, $reload);
        if (isset($preset['name'])) {
            return $preset['name'];
        }

        return null;
    }
} 