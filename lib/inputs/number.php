<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:26
 *
 * @author Shulaev (pavel.shulaev@gmail.com)
 */

namespace Rover\Fadmin\Inputs;

use Rover\Fadmin\Tab;

class Number extends Text
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__NUMBER;

	/**
	 * @var bool
	 */
	public static $cssPrinted = false;

	protected $min;
	protected $max;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

		if (isset($params['min']))
			$this->min = (int)$params['min'];

		if (isset($params['max']))
			$this->max = (int)$params['max'];
	}

	/**
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function draw()
	{
		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId);

		if (!self::$cssPrinted){
			$this->printCss();
			self::$cssPrinted = true;
		}

		?><input
			type="number"
			id="<?php echo $valueId?>"
			size="<?php echo $this->size?>"
			maxlength="<?php $this->maxLength?>"
			value="<?php echo $this->value?>"
			name="<?php echo $valueName?>"
		><?php
		$this->showHelp();
	}

	/**
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	protected function printCss()
	{
		?>
		<style>
			.adm-workarea input[type="number"]{
				background: #fff;
				border: 1px solid;
				border-color: #87919c #959ea9 #9ea7b1 #959ea9;
				border-radius: 4px;
				color: #000;
				-webkit-box-shadow: 0 1px 0 0 rgba(255,255,255,0.3), inset 0 2px 2px -1px rgba(180,188,191,0.7);
				box-shadow: 0 1px 0 0 rgba(255,255,255,0.3), inset 0 2px 2px -1px rgba(180,188,191,0.7);
				display: inline-block;
				outline: none;
				vertical-align: middle;
				-webkit-font-smoothing: antialiased;
				font-size: 13px;
				height: 25px;
				padding: 0 5px;
				margin: 0;
			}
		</style>
		<?php
	}

	/**
	 * @param $value
	 * @return mixed
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	protected function beforeSaveRequest($value)
	{
		// not integer
		if ($value != intval($value))
			$value = $this->default;

		// min
		if (!is_null($this->min) && $value < $this->min)
			$value = $this->default;

		// max
		if (!is_null($this->max) && $value > $this->max)
			$value = $this->default;

		return $value;
	}
}