<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 17.11.2016
 * Time: 18:13
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Options;

use Rover\Fadmin\Options;

/**
 * Class Settings
 *
 * @package Rover\Fadmin\Engine
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Settings
{
	const BOOL_CHECKBOX = 'bool_checkbox';
	const LOG_ERRORS    = 'log_errors';
    const GROUP_RIGHTS  = 'group_rights';
    const USE_SORT      = 'use_sort';
    const PRESET_CLASS  = 'preset_class';

	/**
	 * default settings
	 * @var array
	 */
	protected $defaults = array(
		self::BOOL_CHECKBOX => false,
		self::LOG_ERRORS    => false,
		self::GROUP_RIGHTS  => false,
		self::USE_SORT      => false,
		self::PRESET_CLASS  => '\\Rover\\Fadmin\\Preset',
    );

    /**
     * @var Options
     */
	public $options;

	/**
	 * @param Options $options
	 */
	public function __construct(Options $options)
	{
		$this->options  = $options;
	}

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	protected function init()
    {
        if ($this->options->cache->checkContainer('settings'))
            return;

        $config     = $this->options->getConfigCache();
        $settings   = isset($config['settings'])
                ? $config['settings']
                : array();

        foreach ($this->defaults as $key => $defValue)
        {
            $value = isset($settings[$key])
                ? $settings[$key]
                : $defValue;

            $this->options->cache->set($key, $value, 'settings');
        }
    }

    /**
     * @param $key
     * @return mixed|null
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getFromStorage($key)
    {
        $this->init();

        if ($this->options->cache->check($key, 'settings'))
            return $this->options->cache->get($key, 'settings');

        return null;
    }

    /**
     * @return mixed|null
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function getBoolCheckbox()
	{
		return $this->getFromStorage(self::BOOL_CHECKBOX);
	}

    /**
     * @return mixed|null
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function getLogErrors()
	{
        return $this->getFromStorage(self::LOG_ERRORS);
	}

    /**
     * @return mixed|null
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function getGroupRights()
    {
        return $this->getFromStorage(self::GROUP_RIGHTS);
    }

    /**
     * @return mixed|null
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function getUseSort()
    {
        return $this->getFromStorage(self::USE_SORT);
    }

    /**
     * @return mixed|null
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getPresetClass()
    {
        return $this->getFromStorage(self::PRESET_CLASS);
    }
}