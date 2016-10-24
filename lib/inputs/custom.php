<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:24
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

/**
 * Class Custom
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Custom extends Input
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__CUSTOM;

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw()
	{
		?><tr>
			<td colspan="2"><?=$this->label?></td>
		</tr><?php
	}

	/**
	 * @return bool
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function beforeSaveValue()
	{
		return false;
	}
}