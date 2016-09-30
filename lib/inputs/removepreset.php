<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 15.01.2016
 * Time: 23:03
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Removepreset extends Submit
{
	public static $type = self::TYPE__REMOVE_PRESET;

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw()
	{
		$presetId   = $this->tab->getPresetId();

		if (!$presetId)
			return;

		$siteId     = $this->tab->getSiteId();
		$valueId    = $this->getValueId();

		$this->showLabel($valueId, true);
		$this->drawSubmit($valueId, self::$type, $siteId . self::SEPARATOR . $presetId, $this->label);
		$this->showHelp();

		if ($this->popup === false) return;

		$confirm = $this->popup
			? $this->popup
			: Loc::getMessage('ROVER_OP_REMOVEPRESET_CONFIRM');

		$this->drawConfirm($valueId, $confirm);
	}

	/**
	 * not save
	 * @return bool
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function beforeSaveValue()
	{
		return false;
	}

	/**
	 * value = default value
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function afterLoadValue()
	{
		$this->value = $this->default;
	}
}