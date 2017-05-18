<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:41
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Rover\Fadmin\Tab;

/**
 * Class Textarea
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Textarea extends Input
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__TEXTAREA;

	/**
	 * @var int
	 */
	protected $rows = 3;

	/**
	 * @var int
	 */
	protected $cols = 50;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

		if (isset($params['rows']))
			$this->rows = htmlspecialcharsbx($params['rows']);

		if (isset($params['cols']))
			$this->cols = htmlspecialcharsbx($params['cols']);
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw()
	{
		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId);

		?><textarea
			<?=$this->disabled ? 'disabled="disabled"': '';?>
			id="<?=$valueId?>"
			rows="<?=$this->rows?>"
			cols="<?=$this->cols?>"
			name="<?=$valueName?>"><?=$this->value?></textarea><?php

		$this->showHelp();
	}

	/**
	 * @param $valueId
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function showLabel($valueId)
	{
		?>
		<tr>
		<td
			width="50%"
			style="vertical-align: top; padding-top: 7px;"
			class="adm-detail-valign-top">
			<label for="<?php echo $valueId?>"><?php echo $this->label?>:</label>
		</td>
		<td width="50%"><?php
	}
}