<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:38
 *
 * @author Shulaev (pavel.shulaev@gmail.com)
 */

namespace Rover\Fadmin\Inputs;
use Rover\Fadmin\Tab;

class Header extends Input
{
	public static $type = self::TYPE__HEADER;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		if (!isset($params['name']))
			$params['name'] = 'header_default';

		parent::__construct($params, $tab);
	}

	/**
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function draw()
	{
		?><tr class="heading"><td colspan="2"><?=$this->label?></td></tr><?php
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