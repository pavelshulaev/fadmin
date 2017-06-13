<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 17.11.2016
 * Time: 18:13
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Engine;

use Rover\Fadmin\Options;

class Settings
{
	const BOOL_CHECKBOX     = 'bool_checkbox';
	const LOG_ERRORS        = 'log_errors';
    const GROUP_RIGHTS      = 'group_rights';
	/**
	 * default settings
	 * @var array
	 */
	protected $defaults = [
		self::BOOL_CHECKBOX    => false,
		self::LOG_ERRORS       => false,
		self::GROUP_RIGHTS     => false,
	];

	protected $storage = [];

	public $options;

	/**
	 * @param Options $options
	 */
	public function __construct(Options $options)
	{
		$this->options  = $options;
		$config         = $this->options->getConfig();
		$settings       = isset($config['settings'])
			? $config['settings']
			: [];

		foreach ($this->defaults as $key => $defValue)
			$this->storage[$key] = isset($settings[$key])
				? $settings[$key]
				: $defValue;
	}

	/**
	 * @return mixed
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getBoolCheckbox()
	{
		return $this->storage[self::BOOL_CHECKBOX];
	}

	/**
	 * @return mixed
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getLogErrors()
	{
		return $this->storage[self::LOG_ERRORS];
	}

    /**
     * @return mixed
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function getGroupRights()
    {
        return $this->storage[self::GROUP_RIGHTS];
    }
}