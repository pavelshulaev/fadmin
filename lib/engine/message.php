<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 17.11.2016
 * Time: 18:01
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Engine;

use Bitrix\Main\SystemException;
use Rover\Fadmin\Options;

class Message
{
	/**
	 * message storage
	 * @var array
	 */
	protected $messages = [];

	const TYPE__OK      = 'OK';
	const TYPE__ERROR   = 'ERROR';

	/**
	 * @param Options $options
	 */
	public function __construct(Options $options)
	{
		$this->options = $options;
	}

	/**
	 * @param        $message
	 * @param string $type
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function add($message, $type = self::TYPE__OK)
	{
		$this->messages[] = [
			'MESSAGE'   => htmlspecialcharsbx($message),
			'TYPE'      => htmlspecialcharsbx($type),
		];
	}

	/**
	 * @param $message
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function addOk($message)
	{
		$this->add($message, self::TYPE__OK);
	}

	/**
	 * @param $message
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function addError($message)
	{
		$this->add($message, self::TYPE__ERROR);
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function show()
	{
		foreach ($this->messages as $message)
			\CAdminMessage::ShowMessage($message);
	}
}