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

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Localization\Loc;
use Rover\Fadmin\Options;

Loc::loadMessages(__FILE__);
/**
 * Class Addpreset
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Addpreset extends Submit
{
    /**
     * Addpreset constructor.
     *
     * @param array      $params
     * @param Options    $options
     * @param Input|null $parent
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
	public function __construct(array $params, Options $options, Input $parent = null)
	{
	    if (!isset($params['name']))
		    $params['name'] = self::getType();

		parent::__construct($params, $options, $parent);
	}

    /**
     * @param Event $value
     * @return EventResult|bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeSaveValue(&$value)
	{
		return false;
	}

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function beforeLoadValue()
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