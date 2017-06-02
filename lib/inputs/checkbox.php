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
use Rover\Fadmin\Tab;

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
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

		// add events
		$this->addEventHandler(self::EVENT__AFTER_LOAD_VALUE, [$this, 'afterLoadValue']);
		$this->addEventHandler(self::EVENT__BEFORE_GET_VALUE, [$this, 'beforeGetValue']);
		$this->addEventHandler(self::EVENT__BEFORE_SAVE_REQUEST, [$this, 'beforeSaveRequest']);
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
			type="checkbox"
			<?=$this->disabled ? 'disabled="disabled"': '';?>
			id="<?=$valueId?>"
			name="<?=$valueName?>"
			value="Y"<?=($this->value=="Y")?" checked=\"checked\"":'';?>/><?php

		$this->showHelp();
	}

	/**
	 * @param Event $event
	 * @return \Bitrix\Main\EventResult
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function beforeSaveRequest(Event $event)
	{
		if ($event->getSender() !== $this)
			return $this->getEvent()->getErrorResult($this);

		$value = $event->getParameter('value');

		if ($value !== "Y")
			$value = "N";

		return $this->getEvent()->getSuccessResult($this, compact('value'));
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
	 * @param Event $event
	 * @return bool|void
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function beforeGetValue(Event $event)
	{
		if ($event->getSender() !== $this)
			return;

		if (!$this->tab->options->settings->getBoolCheckbox())
			return;

		$this->value = $this->value == 'Y';
	}
}