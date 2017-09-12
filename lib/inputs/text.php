<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:26
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Rover\Fadmin\Inputs\Params\MaxLength;
use Rover\Fadmin\Inputs\Params\Size;
use Rover\Fadmin\Tab;
/**
 * Class Text
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Text extends Input
{
    use Size, MaxLength;

    /**
	 * @var string
	 */
	public static $type = self::TYPE__TEXT;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

		if (isset($params['maxLength']) && intval($params['maxLength']))
			$this->maxLength = intval($params['maxLength']);
		else
		    $this->maxLength = 255;

		if (isset($params['size']) && intval($params['size']))
			$this->size = intval(htmlspecialcharsbx($params['size']));
		else
		    $this->size = 50;
	}
}