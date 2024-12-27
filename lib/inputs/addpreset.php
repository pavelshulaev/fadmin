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

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Event;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
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
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     */
	public function __construct(array $params, Options $options, Input $parent = null)
	{
	    if (!isset($params['name']))
		    $params['name'] = self::getType();

		parent::__construct($params, $options, $parent);
	}

    /**
     * @param Event $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeSaveValue(&$value): bool
    {
		return false;
	}

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function beforeLoadValue(): bool
    {
        return false;
    }

    /**
     * @param Event $value
     * @return void
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
	public function afterLoadValue(&$value): void
    {
        $value = $this->default;
    }
}