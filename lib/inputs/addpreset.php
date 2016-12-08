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

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Localization\Loc;
use Rover\Fadmin\Tab;

Loc::loadMessages(__FILE__);

/**
 * Class Addpreset
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Addpreset extends Submit
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__ADD_PRESET;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		$params['name'] = self::$type;

		parent::__construct($params, $tab);

		// add events
		$this->addEventHandler(self::EVENT__AFTER_LOAD_VALUE, [$this,   'afterLoadValue']);
		$this->addEventHandler(self::EVENT__BEFORE_SAVE_VALUE, [$this,  'beforeSaveValue']);
	}

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
			: Loc::getMessage('rover-fa__ADDPRESET_TEXT');

		$default = $this->default
			? $this->default
			: Loc::getMessage('rover-fa__ADDPRESET_DEFAULT');

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
						alert('<?php echo Loc::getMessage('rover-fa__ADDPRESET_ALERT')?>');
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
	 * not save
	 * @param Event $event
	 * @return EventResult
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function beforeSaveValue(Event $event)
	{
		return $this->getEvent()->getErrorResult($this);
	}

	/**
	 * value = default value
	 * @param Event $event
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function afterLoadValue(Event $event)
	{
		if ($event->getSender() !== $this)
			return;

		$this->value = $this->default;
	}
}