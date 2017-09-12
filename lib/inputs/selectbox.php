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
use Rover\Fadmin\Tab;

/**
 * Class Selectbox
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Selectbox extends Input
{
    use Size, Options;
    /**
     * @var string
     */
	public static $type = self::TYPE__SELECTBOX;

	const MAX_MULTI_SIZE = 7;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

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