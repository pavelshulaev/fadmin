<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:37
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Options;
/**
 * Class Hidden
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Hidden extends Text
{
    /**
     * Hidden constructor.
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
        if (!isset($params['label']))
            $params['label'] = self::getType();

        parent::__construct($params, $options, $parent);
    }
}