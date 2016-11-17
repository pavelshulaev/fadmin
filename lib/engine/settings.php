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

	/**
	 * @param array $settings
	 */
	public function __construct(array $settings = [])
	{
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