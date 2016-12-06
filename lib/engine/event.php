<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 07.12.2016
 * Time: 2:53
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Engine;

use Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\Event as BxEvent;
use \Bitrix\Main\EventManager;

class Event
{
	/**
	 * @var string
	 */
	protected $moduleId;

	/**
	 * @param $moduleId
	 * @throws ArgumentNullException
	 */
	public function __construct($moduleId)
	{
		if (is_null($moduleId))
			throw new ArgumentNullException('moduleId');

		$this->moduleId = $moduleId;
	}

	/**
	 * @param       $name
	 * @param array $params
	 * @param       $sender
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function send($name, array $params = [], $sender = null)
	{
		(new BxEvent($this->moduleId, $name, $params))->send($sender);
	}

	/**
	 * @param $name
	 * @param $callback
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function addHandler($name, $callback)
	{
		$eventManager = EventManager::getInstance();
		$eventManager->addEventHandler($this->moduleId, $name, $callback);
	}
}