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
/**
 * Class Text
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Text extends Input
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__TEXT;

	/**
	 * @var int
	 */
	protected $maxLength    = 255;
	protected $size         = 50;

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

		if (isset($params['size']) && intval($params['size']))
			$this->size = intval(htmlspecialcharsbx($params['size']));
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw()
	{
		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId);

		?><input
			type="text"
			<?=$this->disabled ? 'disabled="disabled"': '';?>
			id="<?=$valueId?>"
			size="<?=$this->size?>"
			maxlength="<?=$this->maxLength?>"
			value="<?=$this->value?>"
			name="<?=$valueName?>"><?php

		$this->showHelp();
	}
}