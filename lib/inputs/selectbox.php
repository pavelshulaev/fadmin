<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:33
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Rover\Fadmin\Tab;
use Bitrix\Main\Event;
/**
 * Class Selectbox
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Selectbox extends Input
{
	public static $type = self::TYPE__SELECTBOX;

	/**
	 * @var array
	 */
	protected $options = [];

	/**
	 * multiple selectbox size
	 * @var int
	 */
	protected $size = 7;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

		if (isset($params['options']))
			$this->options = $params['options'];

		if (isset($params['size']) && intval($params['size']))
			$this->size = intval($params['size']);
		elseif ($params['multiple'])
			$this->size = count($this->options) > $this->size
				? $this->size
				: count($this->options);
		else
			$this->size = 1;
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw()
	{
		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		if ($this->multiple)
			$this->showMultiLabel($valueId);
		else
			$this->showLabel($valueId);

		?><select
			<?=$this->disabled ? 'disabled="disabled"': '';?>
			name="<?=$valueName . ($this->multiple ? '[]' : '')?>"
			id="<?=$valueId?>"
			size="<?=$this->size?>"
			<?=$this->multiple ? ' multiple="multiple" ' : ''?>
			>
				<?php
				foreach($this->options as $v => $k){
					if ($this->multiple) {
						$selected = is_array($this->value) && in_array($v, $this->value)
								? true
								: false;
					} else {
						$selected = $this->value==$v ? true : false;
					}

					?><option value="<?=$v?>"<?=$selected ? " selected=\"selected\" ": ''?>><?=$k?></option><?php
				}
				?>
			</select>
		<?php
		$this->showHelp();
	}

	/**
	 * @param array $options
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;
	}

	/**
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getOptions()
	{
		return $this->options;
	}

    /**
     * @param $valueId
     * @author Pavel Shulaev (http://rover-it.me)
     */
	protected function showMultiLabel($valueId)
	{
		?>
		<tr>
		<td
			width="50%"
			class="adm-detail-content-cell-l"
			style="vertical-align: top; padding-top: 7px;">
				<label for="<?php echo $valueId?>"><?php echo $this->label?>:<br>
					<img src="/bitrix/images/form/mouse.gif" width="44" height="21" border="0" alt="">
				</label>
		</td>
		<td
			width="50%"
			class="adm-detail-content-cell-r"
			><?php
	}
}