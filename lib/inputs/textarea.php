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
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function showInput()
    {
        ?><textarea
            <?=$this->disabled ? 'disabled="disabled"': '';?>
            id="<?=$this->getValueId()?>"
            rows="<?=$this->rows?>"
            cols="<?=$this->cols?>"
            name="<?=$this->getValueName()?>"><?=$this->value?></textarea><?php
    }

    /**
     * @param bool $empty
     * @author Pavel Shulaev (https://rover-it.me)
     */
	protected function showLabel($empty = false)
	{
        $valueId = $this->getValueId();
		?>
		<tr>
		<td
			width="50%"
			style="vertical-align: top; padding-top: 7px;"
			class="adm-detail-valign-top">
            <?php if (!$empty) : ?>
                <label for="<?=$valueId?>"><?=$this->label?>:</label>
            <?php endif; ?>
		</td>
		<td width="50%"><?php
	}
}