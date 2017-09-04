<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:30
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Rover\Fadmin\Helper\Layout;
use Rover\Fadmin\Tab;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

/**
 * Class Submit
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Submit extends Input
{
	public static $type = self::TYPE__SUBMIT;

	const SEPARATOR = '__';

	/**
	 * @var string
	 */
	protected $popup;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

		if (isset($params['popup']))
			$this->popup = $params['popup'];

		$this->addEventHandler(self::EVENT__AFTER_LOAD_VALUE, [$this, 'afterLoadValue']);
		$this->addEventHandler(self::EVENT__BEFORE_SAVE_VALUE, [$this,  'beforeSaveValue']);
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw()
	{
		parent::draw();

		if (!$this->popup)
			return;

		$this->drawConfirm($this->getValueId(), $this->popup);
	}

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function showInput()
    {
        Layout::submit($this);
    }

	/**
	 * @param $id
	 * @param $popup
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function drawConfirm($id, $popup)
	{
		?>
		<script>
			(function(){
				document.getElementById('<?=$id?>').onclick = function(){
					return confirm('<?=$popup?>');
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