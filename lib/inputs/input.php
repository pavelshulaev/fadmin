<?php
namespace Rover\Fadmin\Inputs;

use Bitrix\Main;
use Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;
use \Rover\Fadmin\Tab;
use \Rover\Fadmin\Options;
/**
 * Class Input
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
abstract class Input
{
	const EVENT__BEFORE_SAVE_REQUEST    = 'beforeSaveRequest';
	const EVENT__BEFORE_SAVE_VALUE      = 'beforeSaveValue';
	const EVENT__BEFORE_GET_VALUE       = 'beforeGetValue';
	const EVENT__AFTER_LOAD_VALUE       = 'afterLoadValue';

	const TYPE__HIDDEN = 'hidden';
	const TYPE__DATE = 'date';
	const TYPE__DATETIME = 'datetime';
	const TYPE__LABEL = 'label';
	const TYPE__HEADER = 'header';
	const TYPE__CHECKBOX = 'checkbox';
	const TYPE__TEXT = 'text';
	const TYPE__NUMBER = 'number';
	const TYPE__FILE = 'file';
	const TYPE__COLOR = 'color';
	const TYPE__IBLOCK = 'iblock';
	const TYPE__TEXTAREA = 'textarea';
	const TYPE__SELECTBOX = 'selectbox';
	const TYPE__SUBMIT = 'submit';
	const TYPE__ADD_PRESET = 'addpreset';
	const TYPE__REMOVE_PRESET = 'removepreset';
	const TYPE__CUSTOM = 'custom';
	const TYPE__CLOCK = 'clock';
	const TYPE__PRESET_NAME = 'presetname';

	protected $id;                  // input id
	protected $name;                // input name (required)
	protected $label;               // input label
	protected $value;               // input value
	protected $default;             // default input value
	protected $multiple = false;    // multiple value for selectbox and iblock
	protected $help;                // help

	/**
	 * @var Tab
	 */
	protected $tab;

	/**
	 *  $params = [
	 *      'id'        => input id (required)
	 *      'name'      =>
	 *      'default'   =>
	 *      'multiple'  =>
	 *      'size'      => input/file input size
	 *      'siteId'    => siteId,
	 *      'help'      => help info
	 * ]
	 *
	 * @param array $params
	 * @param Tab   $tab
	 * @throws Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		if (is_null($params['name']))
			throw new Main\ArgumentNullException('name');

		if (is_null($params['label']))
			throw new Main\ArgumentNullException('label');

		if (is_null($params['id']))
			$params['id'] = $params['name'];

		$this->tab = $tab;

		$this->id   = htmlspecialcharsbx($params['id']);
		$this->name = htmlspecialcharsbx($params['name']);

		$this->setLabel($params['label']);
		$this->setDefault($params['default']);

		if (isset($params['multiple']))
			$this->multiple = (bool)$params['multiple'];

		if (isset($params['help']))
			$this->help = $params['help'];

		if (method_exists($this, 'addEventsHandlers'))
			$this->addEventsHandlers();
	}

	/**
	 * @param $name
	 * @param $method
	 * @return int|mixed
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function addEventHandler($name, $method)
	{
		return \Bitrix\Main\EventManager::getInstance()->addEventHandler($this->getModuleId(), $name, [$this, $method]);
	}

	/**
	 * @param Tab $tab
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function setTab(Tab $tab)
	{
		$this->tab = $tab;
	}

	/**
	 * @return Tab
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getTab()
	{
		return $this->tab;
	}

	/**
	 * loading value before showing input
	 *
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function show()
	{
		$this->loadValue();
		$this->draw();
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	abstract public function draw();

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @return mixed
	 * @throws Main\SystemException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function factory(array $params, Tab $tab)
	{
		$className = '\Rover\Fadmin\Inputs\\' . ucfirst($params['type']);

		if (!class_exists($className))
			throw new Main\SystemException('Class "' . $className . '" not found!');

		if ($className == '\Rover\Fadmin\Inputs\Input')
			throw new Main\SystemException('Can\'t create "' . $className . '" instance');

		$input = new $className($params, $tab);

		if ($input instanceof Input === false)
			throw new Main\SystemException('"' . $className . '" is not "\Rover\Fadmin\Inputs\Input" instance');

		return $input;
	}

	/**
	 * @return mixed
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param $label
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function setLabel($label)
	{
		$this->label = trim($label);
	}

	/**
	 * @return mixed
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getDefault()
	{
		return $this->default;
	}

	/**
	 * @param $default
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function setDefault($default)
	{
		if (is_array($default))
			$default = serialize($default);

		$this->default = trim($default);
	}

	/**
	 * @return bool
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function isMultiple()
	{
		return $this->multiple;
	}

	/**
	 * @param $value
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function setValue($value)
	{
		$this->value = $this->saveValue($value)
		    ? $value
			: null;
	}

	/**
	 * @param $value
	 * @return bool
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function saveValue($value)
	{
		$result = $this->getEvent()->getResult(self::EVENT__BEFORE_SAVE_VALUE,
			compact('value'), $this);

		if ($result === false)
			return false;

		if (is_array($result) && array_key_exists('value', $result))
			$value = $result['value'];

		Option::set($this->tab->getModuleId(), $this->getValueName(), $value, $this->tab->getSiteId());

		return true;
	}

	/**
	 * @throws Main\ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function removeValue()
	{
		$this->value = null;
		$filter      = array('name' => $this->getValueName(), 'site_id' => $this->tab->getSiteId());

		Option::delete($this->tab->getModuleId(), $filter);
	}

	/**
	 * @return \Rover\Fadmin\Engine\Event
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function getEvent()
	{
		return $this->tab->options->event;
	}

	/**
	 * @return Options
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function getOptions()
	{
		return $this->tab->options;
	}

	/**
	 * @throws Main\ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function loadValue()
	{
		$this->value = Option::get($this->tab->getModuleId(),
			$this->getValueName(), $this->default, $this->tab->getSiteId());

		$this->getEvent()->send(self::EVENT__AFTER_LOAD_VALUE, [], $this);

		if ($this->multiple) {
			if (!is_array($this->value))
				$this->value = unserialize($this->value);

			if (!$this->value)
				$this->value = [];
		}
	}

	/**
	 * @param array  $params
	 * @param        $moduleId
	 * @param string $presetId
	 * @param string $siteId
	 * @return string
	 * @throws Main\ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getValueStatic(array $params, $moduleId, $presetId = '', $siteId = '')
	{
		if (!isset($params['name']))
			throw new Main\ArgumentNullException('name');

		if (!isset($params['default']))
			$params['default'] = null;

		return Option::get($moduleId,
			Options::getFullName($params['name'], $presetId, $siteId),
			$params['default'], $siteId);
	}

	/**
	 * @param bool|false $reload
	 * @return mixed
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getValue($reload = false)
	{
		if (empty($this->value) || $reload)
			$this->loadValue();

		$this->getEvent()->send(self::EVENT__BEFORE_GET_VALUE, [], $this);

		return $this->value;
	}

	/**
	 * @return mixed
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getType()
	{
		return static::$type;
	}

	/**
	 * @return string
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getValueId()
	{
		return Options::getFullName($this->id,
			$this->tab->getPresetId(), $this->tab->getSiteId());
	}


	/**
	 * @return string
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getValueName()
	{
		return Options::getFullName($this->name,
			$this->tab->getPresetId(), $this->tab->getSiteId());
	}

	/**
	 * @return mixed
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * can be redefined in children
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function setValueFromRequest()
	{
		$value = $this->getValueFromRequest();

		// EVENT__BEFORE_SAVE_REQUEST
		$params = $this->getEvent()->getResult(self::EVENT__BEFORE_SAVE_REQUEST, compact('value'), $this);
		if (isset($params['value']))
			$value = $params['value'];

		//serialize multiple value
		if ($this->multiple && is_array($value))
			$value = serialize($value);

		$this->setValue($value);
	}

	/**
	 * @param $name
	 * @param $params
	 * @return mixed
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function runEvent($name, &$params = [])
	{
		if (method_exists($this, $name))
			return $this->$name($params);

		return true;
	}

	/**
	 * @return null|string
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function getValueFromRequest()
	{
		$request = Application::getInstance()
			->getContext()
			->getRequest();

		return $request->get($this->getValueName());
	}

	/**
	 * @param            $valueId
	 * @param bool|false $empty
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function showLabel($valueId, $empty = false)
	{
		?>
		<tr>
		<td
			width="50%"
			class="adm-detail-content-cell-l"
			style="vertical-align: top; padding-top: 7px;">
			<?php if (!$empty) : ?>
				<label for="<?php echo $valueId?>"><?php echo $this->label?>:</label>
			<?php endif; ?>
		</td>
		<td
		width="50%"
		class="adm-detail-content-cell-r"
		><?php
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function showHelp()
	{
		if (strlen($this->help)){
			echo '<br><small>' . $this->help . '</small>';
		}
		?></td>
		</tr>
		<?php
	}

	/**
	 * @return string
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getModuleId()
	{
		return $this->tab->getModuleId();
	}
}