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
     * @param array                 $params
     * @param \Rover\Fadmin\Options $optionsEngine
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
	public function __construct(array $params, \Rover\Fadmin\Options $optionsEngine)
	{
		parent::__construct($params, $optionsEngine);

		if (isset($params['options']))
			$this->options = $params['options'];
	}
}