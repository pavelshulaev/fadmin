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

use Rover\Fadmin\Options;

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
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function afterLoadValue()
	{
		$this->value = $this->value == 'Y' ? 'Y' : 'N';
	}

	/**
	 * @return bool
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function beforeGetValue()
	{
		$settings = $this->tab->getOptions()->getSettings();

		if (isset($settings[Options::SETTINGS__CHECKBOX_BOOLEAN])
			&& $settings[Options::SETTINGS__CHECKBOX_BOOLEAN])
			return $this->value == 'Y';

		return $this->value;
	}
}