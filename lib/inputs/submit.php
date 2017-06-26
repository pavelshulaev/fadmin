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
		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId, true);
		$this->drawSubmit($valueId, $valueName, $this->default, $this->label);
		$this->showHelp();

		if (!$this->popup)
			return;

		$this->drawConfirm($valueId, $this->popup);
	}

	/**
	 * @param $id
	 * @param $name
	 * @param $value
	 * @param $label
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function drawSubmit($id, $name, $value, $label)
	{
		?>
		<style>
			button[name="<?=$name?>"]{
			    -webkit-border-radius: 4px;
			    border-radius: 4px;
			    border: none;
			    /* border-top: 1px solid #fff; */
			    -webkit-box-shadow: 0 0 1px rgba(0,0,0,.11), 0 1px 1px rgba(0,0,0,.3), inset 0 1px #fff, inset 0 0 1px rgba(255,255,255,.5);
			    box-shadow: 0 0 1px rgba(0,0,0,.3), 0 1px 1px rgba(0,0,0,.3), inset 0 1px 0 #fff, inset 0 0 1px rgba(255,255,255,.5);
			    background-color: #e0e9ec;
			    background-image: -webkit-linear-gradient(bottom, #d7e3e7, #fff)!important;
			    background-image: -moz-linear-gradient(bottom, #d7e3e7, #fff)!important;
			    background-image: -ms-linear-gradient(bottom, #d7e3e7, #fff)!important;
			    background-image: -o-linear-gradient(bottom, #d7e3e7, #fff)!important;
			    background-image: linear-gradient(bottom, #d7e3e7, #fff)!important;
			    color: #3f4b54;
			    cursor: pointer;
			    display: inline-block;
			    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
			    font-weight: bold;
			    font-size: 13px;
			    /* line-height: 18px; */
			    height: 29px;
			    text-shadow: 0 1px rgba(255,255,255,0.7);
			    text-decoration: none;
			    position: relative;
			    vertical-align: middle;
			    -webkit-font-smoothing: antialiased;
			    padding: 1px 13px 3px;
			}

			button[name=<?=$name?>]:hover{
				text-decoration: none;
				background: #f3f6f7!important;
				background-image: -webkit-linear-gradient(top, #f8f8f9, #f2f6f8)!important;
				background-image: -moz-linear-gradient(top, #f8f8f9, #f2f6f8)!important;
				background-image: -ms-linear-gradient(top, #f8f8f9, #f2f6f8)!important;
				background-image: -o-linear-gradient(top, #f8f8f9, #f2f6f8)!important;
				background-image: linear-gradient(top, #f8f8f9, #f2f6f8)!important;
			}
		</style>

		<button type='submit'
				<?=$this->disabled ? 'disabled="disabled"': '';?>
				id="<?=$id?>"
				name="<?=$name?>"
				value="<?=urlencode($value)?>"><?php echo $label?></button><?php
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
				document.getElementById('<?php echo $id?>').onclick = function(){
					return confirm('<?php echo $popup?>');
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