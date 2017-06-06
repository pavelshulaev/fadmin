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

/**
 * Class Settings
 *
 * @package Rover\Fadmin\Engine
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Settings
{
	const BOOL_CHECKBOX    = 'bool_checkbox';
	const LOG_ERRORS       = 'log_errors';

	/**
	 * default settings
	 * @var array
	 */
	protected $defaults = [
		self::BOOL_CHECKBOX    => false,
		self::LOG_ERRORS       => false
	];

	protected $storage = [];

	public $options;

	/**
	 * @param Options $options
	 */
	public function __construct(Options $options)
	{
		$this->options  = $options;
		$config         = $this->options->getConfigCache();
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
}