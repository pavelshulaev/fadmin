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
use Rover\Fadmin\Options;
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
     * Removepreset constructor.
     *
     * @param array   $params
     * @param Options $options
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
	public function __construct(array $params, Options $options)
	{
	    if (!isset($params['name']))
		    $params['name'] = self::getType();

		parent::__construct($params, $options);
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