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

use Rover\Fadmin\Options;

/**
 * Class Message
 *
 * @package Rover\Fadmin\Engine
 * @author  Pavel Shulaev (http://rover-it.me)
 */
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
     * @param bool   $html
     * @author Pavel Shulaev (http://rover-it.me)
     */
	public function add($message, $type = self::TYPE__OK, $html = true)
	{
	    if (is_array($message))
	        $message = implode("\n", $message);

	    if (!$html)
	        $message = htmlspecialcharsbx($message);

		$this->messages[] = [
			'MESSAGE'   => trim($message),
            'HTML'      => (bool)$html,
			'TYPE'      => htmlspecialcharsbx($type),
		];
	}

    /**
     * @param      $message
     * @param bool $html
     * @author Pavel Shulaev (http://rover-it.me)
     */
	public function addOk($message, $html = false)
	{
		$this->add($message, self::TYPE__OK, $html);
	}

    /**
     * @param      $message
     * @param bool $html
     * @author Pavel Shulaev (http://rover-it.me)
     */
	public function addError($message, $html = false)
	{
		$this->add($message, self::TYPE__ERROR, $html);
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