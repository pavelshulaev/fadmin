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
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public function __construct(array $params, Options $options, Input $parent = null)
    {
        if (!isset($params['label']))
            $params['label'] = self::getType();

        parent::__construct($params, $options, $parent);
    }
}