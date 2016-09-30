<?php
namespace Rover\Fadmin\Inputs;
use Rover\Fadmin\Tab;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:33
 *
 * @author Shulaev (pavel.shulaev@gmail.com)
 */
class Selectbox extends Input
{
	public static $type = self::TYPE__SELECTBOX;

	protected $options = [];

	const MAX_SIZE = 8;

	/**
	 * @param array $params
	 */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

		if (isset($params['options']))
			$this->options = $params['options'];
	}

	/**
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function draw()
	{
		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId);

		if ($this->multiple) {
			$size = count($this->options) > self::MAX_SIZE
				? self::MAX_SIZE
				: count($this->options);
		} else {
			$size = 1;
		}


		?><select
			name="<?php echo $valueName . ($this->multiple ? '[]' : '')?>"
			id="<?php echo $valueId?>"
			<?php echo $this->multiple
				? ' multiple="multiple" size="' . $size . '" '
				: ''
			?>>
				<?php
				foreach($this->options as $v => $k){
					if ($this->multiple) {
						$selected = is_array($this->value) && in_array($v, $this->value)
								? true
								: false;
					} else {
						$selected = $this->value==$v ? true : false;
					}

					?><option value="<?=$v?>"<?php if($selected) echo" selected=\"selected\" ";?>><?=$k?></option><?php
				}
				?>
			</select>
		<?php
		$this->showHelp();
	}

	/**
	 * @param $value
	 * @return string
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	protected function beforeSaveRequest($value)
	{
		if ($this->multiple)
			$value = serialize($value);

		return $value;
	}

	/**
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	protected function afterLoadValue()
	{
		if ($this->multiple){
			$this->value = unserialize($this->value);

			if (is_null($this->value))
				$this->value = [];
		}
	}
}