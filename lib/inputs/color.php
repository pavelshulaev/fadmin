<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:02
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;
/**
 * Class Color
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Color extends Input
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__COLOR;

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        ?><input
            <?=$this->disabled ? 'disabled="disabled"': '';?>
            id="<?=$this->getValueId()?>"
            type="color"
            value="<?=$this->value?>"
            name="<?=$this->getValueName()?>"><?php
    }
}