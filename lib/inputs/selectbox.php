<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:33
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Inputs\Params\Options;
use Rover\Fadmin\Inputs\Params\Size;
use Rover\Fadmin\Options as OptionsEngine;


/**
 * Class Selectbox
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Selectbox extends Input
{
    use Options, Size;

    const MAX_MULTI_SIZE = 7;

    /**
     * Selectbox constructor.
     *
     * @param array $params
     * @param OptionsEngine $optionsEngine
     * @param Input|null $parent
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     */
    public function __construct(array $params, OptionsEngine $optionsEngine, Input $parent = null)
    {
        parent::__construct($params, $optionsEngine, $parent);

        if (isset($params['options'])) {
            $this->setOptions($params['options']);
        }

        if (isset($params['size']) && intval($params['size'])) {
            $this->setSize($params['size']);
        } elseif ($params['multiple']) {
            $size = min(count($this->options), self::MAX_MULTI_SIZE);
            $this->setSize($size);
        } else {
            $this->setSize(1);
        }
    }
}