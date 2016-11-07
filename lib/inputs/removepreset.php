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
use Rover\Fadmin\Tab;

Loc::loadMessages(__FILE__);

/**
 * Class Removepreset
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Removepreset extends Submit
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__REMOVE_PRESET;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		$params['name'] = self::$type;

		parent::__construct($params, $tab);
	}

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
			: Loc::getMessage('rover-fa__REMOVEPRESET_CONFIRM');

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