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

/**
 * Class Selectbox
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Radio extends Input
{
    use Options;

    /**
     * Radio constructor.
     *
     * @param array $params
     * @param \Rover\Fadmin\Options $optionsEngine
     * @param Input|null $parent
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     */
    public function __construct(array $params, \Rover\Fadmin\Options $optionsEngine, Input $parent = null)
    {
        parent::__construct($params, $optionsEngine, $parent);

        if (isset($params['options'])) {
            $this->setOptions($params['options']);
        }
    }
}