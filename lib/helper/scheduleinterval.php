<?php
namespace Rover\Fadmin\Helper;
use Bitrix\Main\Type\DateTime;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 15.12.2016
 * Time: 22:47
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */
class ScheduleInterval
{
	protected $dayOfWeek;
	protected $timeStart;
	protected $timeEnd;


	public function __construct($dayOfWeek, $timeStart, $timeEnd)
	{
		//$this->dateStart = $dateStart;
		//$this->dateEnd = $dateEnd;
	}

}