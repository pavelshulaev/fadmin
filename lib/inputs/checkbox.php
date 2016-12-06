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

use \Bitrix\Main\Event;
/**
 * Class Checkbox
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Checkbox extends Input
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__CHECKBOX;

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function addEventsHandlers()
	{
		$event = $this->getEvent();

		$event->addHandler(self::EVENT__AFTER_LOAD_VALUE, [$this, 'afterLoadValue']);
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw()
	{
		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId);

		?><input type="checkbox" id="<?php echo $valueId?>" name="<?php echo $valueName?>" value="Y"<?=($this->value=="Y")?" checked=\"checked\"":'';?>/><?php

		$this->showHelp();
	}

	/**
	 * @param $value
	 * @return string
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function beforeSaveRequest($value)
	{
		if ($value !== "Y")
			$value = "N";

		return $value;
	}

	/**
	 * @param Event $event
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function afterLoadValue(Event $event)
	{
		if ($event->getSender() !== $this)
			return;

		$this->value = $this->value == 'Y' ? 'Y' : 'N';
	}

	/**
	 * @return bool
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function beforeGetValue()
	{
		$settings = $this->tab->options->settings;

		if ($settings->getBoolCheckbox())
			return $this->value == 'Y';

		return $this->value;
	}
}