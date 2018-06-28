<?php
namespace Rover\Fadmin\Options;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use \Bitrix\Main\Config\Option;
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

	/**
	 * @var string
	 */
	protected $options;

    /**
     * @var array
     */
	protected $presets;

	/**
	 * @param Options $options
	 */
	public function __construct(Options $options)
	{
		$this->options = $options;
	}

    /**
     * @param string $siteId
     * @param bool   $reload
     * @return mixed
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function getList($siteId = '', $reload = false)
	{
	    if (is_null($this->presets[$siteId]) || $reload)
	        $this->presets[$siteId] = unserialize(Option::get($this->options->getModuleId(),
                self::OPTION_ID, '', $siteId));

		return $this->presets[$siteId];
	}

    /**
     * @param string $siteId
     * @param bool   $reload
     * @return array
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function getInstancesList($siteId = '', $reload = false)
    {
        $presets    = $this->getList($siteId);
        $list       = array();
        $presetClass= $this->options->settings->getPresetClass();

        foreach ($presets as $preset){
            $presetInstance = $presetClass::getInstance($preset['id'], $this->options, $reload);

            if (!$presetInstance instanceof \Rover\Fadmin\Preset)
                throw new ArgumentOutOfRangeException('presetInstance');

            $list[$preset['id']] = $presetInstance;
        }

        return $list;
    }

    /**
     * @param string $siteId
     * @param bool   $reload
     * @return array
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function getIds($siteId = '', $reload = false)
	{
		return array_keys($this->getList($siteId, $reload));
	}

    /**
     * @param        $id
     * @param string $siteId
     * @param bool   $reload
     * @return null
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function getById($id, $siteId = '', $reload = false)
	{
        $id = intval($id);
        if (!$id)
            throw new ArgumentNullException('id');

		$presets = $this->getList($siteId, $reload);

		if (isset($presets[$id]))
			return $presets[$id];

		return null;
	}

    /**
     * @param string $siteId
     * @param bool   $reload
     * @return int
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function getCount($siteId = '', $reload = false)
	{
		return count($this->getList($siteId, $reload));
	}

    /**
     * @param        $value
     * @param string $siteId
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function add($value, $siteId = '')
	{
        if (!$this->options->event
            ->handle(Event::BEFORE_ADD_PRESET, compact('siteId', 'value'))
            ->isSuccess())
            return false;

        $params = $this->options->event->getParameters();

        if (!isset($params['name']))
            $params['name'] = $params['value'];

        $name       = trim($params['name']);
		$presets    = $this->getList($params['siteId'], true);

		if (!count($presets)){
			$presets    = array();
			$id   = 1;
		} else
            $id   = max(array_keys($presets)) + 1;

		$presets[$id] = array(
			'id'    => $id,
			'name'  => htmlspecialcharsbx($name)
        );

		$this->update($presets, $siteId);

        $params = $this->options->event
            ->handle(Event::AFTER_ADD_PRESET, compact('id', 'value'))
            ->getParameters();

        // reload tabs after event!!!
        $this->options->getTabControl()->reloadTabs();

		return $params['id'];
	}

    /**
     * @param        $id
     * @param string $siteId
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function remove($id, $siteId = '')
	{
        $id = intval($id);
        if (!$id)
            throw new ArgumentNullException('id');

        // action beforeRemovePreset
        if (!$this->options->event
            ->handle(Event::BEFORE_REMOVE_PRESET, compact('siteId', 'id'))
            ->isSuccess())
            return false;

        $params     = $this->options->event->getParameters();
        /** @var Tab $presetTab */
        $presetTab  = $this->options->getTabControl()
            ->getTabByPresetId($params['id'], $params['siteId']);

        if ($presetTab instanceof Tab === false)
            throw new ArgumentOutOfRangeException('tab');

        $presetTab->clear();

		$presets = $this->getList($siteId, true);

		foreach ($presets as $num => $preset){
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
     * @param        $presets
     * @param string $siteId
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	protected function update($presets, $siteId = '')
	{
		Option::set($this->options->getModuleId(),
			self::OPTION_ID, serialize($presets), $siteId);

		// reset cache
		$this->presets = null;
	}

    /**
     * @param        $sortFunc
     * @param string $siteId
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function sort($sortFunc, $siteId = '')
	{
		$presets = $this->getList($siteId, true);
		usort($presets, $sortFunc);
		$this->update($presets, $siteId);
	}

    /**
     * @param        $id
     * @param string $siteId
     * @param bool   $reload
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function isExists($id, $siteId = '', $reload = false)
	{
        $id = intval($id);
        if (!$id)
            return false;

		return in_array($id, $this->getIds($siteId, $reload));
	}

    /**
     * @param        $id
     * @param        $name
     * @param string $siteId
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function updateName($id, $name, $siteId = '')
	{
        $id = intval($id);
		if (!$id)
			throw new ArgumentNullException('id');

		$name = trim($name);
		if (!$name)
			throw new ArgumentNullException('name');

		$presets = $this->getList($siteId, true);

		foreach ($presets as $num => &$preset){
			if ($preset['id'] != $id)
				continue;

			$preset['name'] = $name;
			break;
		}

		$this->update($presets, $siteId);
	}

    /**
     * @param        $id
     * @param string $siteId
     * @param bool   $reload
     * @return null
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function getNameById($id, $siteId = '', $reload = false)
	{
		$preset = $this->getById($id, $siteId, $reload);
		if (isset($preset['name']))
			return $preset['name'];

		return null;
	}
} 