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
		?><button type='submit'
                  id="<?php echo $id?>"
                  name="<?php echo $name?>"
                  value="<?php echo urlencode($value)?>"><?php echo $label?></button><?php
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
	 * @return bool
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function beforeSaveValue()
	{
		return false;
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function afterLoadValue()
	{
		$this->value = $this->default;
	}
}