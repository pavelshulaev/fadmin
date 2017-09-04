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
use Rover\Fadmin\Helper\Layout;
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
		$this->showLabel(true);
		$this->showInput();
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
				document.getElementById('<?=$this->getValueId()?>').onclick = function()
				{
					var presetName = prompt('<?=$text ?>', '<?=$default?>');

					if (presetName == null)
						return false;

					if (!presetName.length) {
						alert('<?=Loc::getMessage('rover-fa__ADDPRESET_ALERT')?>');
						return false;
					}

					this.setAttribute('value', '<?=$this->tab->getSiteId() . self::SEPARATOR?>' + presetName);
					return true;
				}
			})();
		</script>
		<?php
	}

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        Layout::submit($this, self::$type, $this->tab->getSiteId() . self::SEPARATOR . $this->default);
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