<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 15.01.2016
 * Time: 23:03
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Main\Localization\Loc;
use Rover\Fadmin\Tab;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

Loc::loadMessages(__FILE__);
/**
 * Class Removepreset
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Removepreset extends Submit
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__REMOVE_PRESET;

    /**
     * Removepreset constructor.
     *
     * @param array $params
     * @param Tab   $tab
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
	public function __construct(array $params, Tab $tab)
	{
		$params['name'] = self::$type;

		parent::__construct($params, $tab);
    }

    /**
     * @param Event $value
     * @return EventResult|bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
	public function beforeSaveValue(&$value)
	{
		return false;
	}

    /**
     * @param Event $value
     * @return bool|void
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
	public function afterLoadValue(&$value)
	{
		$value = $this->default;
	}
}