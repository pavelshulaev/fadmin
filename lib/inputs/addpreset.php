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

class Addpreset extends Submit
{
	public static $type = self::TYPE__ADD_PRESET;

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw()
	{
		$valueId    = $this->getValueId();
		$siteId     = $this->tab->getSiteId();

		$this->showLabel($valueId, true);
		$this->drawSubmit($valueId, self::$type, $siteId . self::SEPARATOR . $this->default, $this->label);
		$this->showHelp();

		if ($this->popup === false) return;

		$text = $this->popup
			? $this->popup
			: Loc::getMessage('ROVER_OP_ADDPRESET_TEXT');

		$default = $this->default
			? $this->default
			: Loc::getMessage('ROVER_OP_ADDPRESET_DEFAULT');

		?>
		<script>
			(function()
			{
				document.getElementById('<?php echo $valueId?>').onclick = function()
				{
					var presetName = prompt('<?php echo $text ?>', '<?php echo $default?>');

					if (presetName == null)
						return false;

					if (!presetName.length) {
						alert('<?php echo Loc::getMessage('ROVER_OP_ADDPRESET_ALERT')?>');
						return false;
					}

					this.setAttribute('value', '<?php echo $siteId . self::SEPARATOR?>' + presetName);
					return true;
				}
			})();
		</script>
		<?php
	}

	/**
	 *  not save
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