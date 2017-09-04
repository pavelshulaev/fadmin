<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:50
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

/**
 * Class Clock
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Date extends DateTime
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__DATE;

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function showInput()
    {
        $this->hideTime();
        parent::showInput();
    }
}