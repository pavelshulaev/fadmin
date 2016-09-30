<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:02
 *
 * @author Shulaev (pavel.shulaev@gmail.com)
 */

namespace Rover\Fadmin\Inputs;

class Color extends Input
{
	public static $type = self::TYPE__COLOR;

	/**
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function draw()
	{
		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId);

		?><input id="<?php echo $valueId?>" type="color" value="<?php echo $this->value?>" name="<?php echo $valueName?>"><?php

		$this->showHelp();
	}
}