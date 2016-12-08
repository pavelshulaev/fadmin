<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 07.12.2016
 * Time: 1:06
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Rover\Fadmin\Presets;
use Rover\Fadmin\Tab;
use Bitrix\Main\Event;

class PresetName extends Text
{
	public static $type = self::TYPE__PRESET_NAME;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentOutOfRangeException
	 */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

		if (!$this->tab->isPreset())
			return;

		$presetId = $this->tab->getPresetId();

		if (!$presetId)
			return;

		$this->setValue($this->tab->options
			->getPresetNameById($presetId, $this->tab->getSiteId()));
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function addEventsHandlers()
	{
		$event = $this->getEvent();

		$event->addHandler(self::EVENT__BEFORE_SAVE_REQUEST, [$this, 'beforeSaveRequest']);
	}

	/**
	 * @param Event $event
	 * @return \Bitrix\Main\EventResult|bool
	 * @throws \Bitrix\Main\ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function beforeSaveRequest(Event $event)
	{
		if ($event->getSender() !== $this)
			return $this->getEvent()->getErrorResult($this);

		if (!$this->tab->isPreset())
			return true;

		$presetId = $this->tab->getPresetId();

		if (!$presetId)
			return true;

		$value = $event->getParameter('value');

		Presets::updateName($presetId, $value,
			$this->tab->getModuleId(), $this->tab->getSiteId());

		return $this->getEvent()->getSuccessResult($this, compact('value'));
	}
}