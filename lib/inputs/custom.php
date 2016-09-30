<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:24
 *
 * @author Shulaev (pavel.shulaev@gmail.com)
 */

namespace Rover\Fadmin\Inputs;

class Custom extends Input
{
	public static $type = self::TYPE__CUSTOM;

	/**
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function draw()
	{
		?><tr>
			<td colspan="2"><?=$this->label?></td>
		</tr><?php
	}

	/**
	 * @return bool
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	protected function beforeSaveValue()
	{
		return false;
	}
}