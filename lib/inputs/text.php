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

use Rover\Fadmin\Tab;

class Text extends Input
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__TEXT;

	/**
	 * @var int
	 */
	protected $maxLength = 255;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

		$this->maxLength = isset($params['maxLength'])
			? htmlspecialcharsbx($params['maxLength'])
			: null;
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw()
	{
		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId);

		?><input type="text"
		         id="<?php echo $valueId?>"
		         size="<?php echo $this->size?>"
		         maxlength="<?php $this->maxLength?>"
		         value="<?php echo $this->value?>"
		         name="<?php echo $valueName?>"><?php

		$this->showHelp();
	}
}