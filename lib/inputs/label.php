<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:26
 *
 * @author Shulaev (pavel.shulaev@gmail.com)
 */

namespace Rover\Fadmin\Inputs;
use Rover\Fadmin\Tab;

class Label extends Input
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__LABEL;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		if (!isset($params['name']))
			$params['name'] = 'label_default';

		parent::__construct($params, $tab);
	}


	/**
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function draw()
	{
		$this->showLabel($this->getValueId());

		echo $this->default;

		$this->showHelp();
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