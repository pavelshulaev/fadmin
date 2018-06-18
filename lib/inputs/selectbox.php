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
     * @param array         $params
     * @param OptionsEngine $optionsEngine
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
	public function __construct(array $params, OptionsEngine $optionsEngine)
	{
		parent::__construct($params, $optionsEngine);

		if (isset($params['options']))
			$this->options = $params['options'];

		if (isset($params['size']) && intval($params['size']))
			$this->size = intval($params['size']);
		elseif ($params['multiple'])
			$this->size = count($this->options) > self::MAX_MULTI_SIZE
				? self::MAX_MULTI_SIZE
				: count($this->options);
		else
			$this->size = 1;
	}
}