<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:34
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin;

use \Rover\Fadmin\Inputs\Input;
use Bitrix\Main;
use Bitrix\Main\ArgumentNullException;

/**
 * Class Tab
 *
 * @package Rover\Fadmin
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Tab
{
	/**
	 * inputs container
	 * @var array
	 */
	protected $inputs = [];

	protected $id;
	protected $name;
	protected $label;
	protected $description;
	protected $preset;
	protected $presetId = '';
	protected $siteId = '';

	/**
	 * @var Options
	 */
	protected $options;

	/**
	 * @param array   $params
	 * @param Options $options
	 * @throws ArgumentNullException
	 */
	public function __construct(array $params, Options $options)
	{
		//if (is_null($params['moduleId']))
		//	throw new ArgumentNullException('moduleId');

		if (is_null($params['name']))
			throw new ArgumentNullException('name');

		if (is_null($params['label']))
			throw new ArgumentNullException('label');

		$this->options      = $options;
		//$this->moduleId     = htmlspecialcharsbx($params['moduleId']);
		$this->name         = htmlspecialcharsbx($params['name']);
		$this->setLabel($params['label']);

		$this->preset = isset($params['preset'])
			? (bool)$params['preset']
			: false;

		$this->setPresetId($params['presetId']);
		$this->setDescription($params['description']);
		$this->setSiteId($params['siteId']);
	}

	/**
	 * @return Options
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * @param array   $params
	 * @param Options $options
	 * @return Tab
	 * @throws Main\SystemException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function factory(array $params, Options $options)
	{
		$tab = new Tab($params, $options);

		if (isset($params['inputs']) && is_array($params['inputs']))
			foreach ($params['inputs'] as $inputParams) {
				$tab->addInput(Input::factory($inputParams, $tab));
			}


		return $tab;
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
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param $description
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function setDescription($description = '')
	{
		$this->description = trim($description);
	}

	/**
	 * @return string
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getPresetId()
	{
		return $this->presetId;
	}

	/**
	 * @param $presetId
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function setPresetId($presetId = '')
	{
		$this->presetId = htmlspecialcharsbx($presetId);
	}

	/**
	 * @return bool
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function isPreset()
	{
		return (bool)$this->preset;
	}

	/**
	 *
	 * @return mixed
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getSiteId()
	{
		return $this->siteId;
	}

	/**
	 * @param $siteId
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function setSiteId($siteId = '')
	{
		$this->siteId = htmlspecialcharsbx($siteId);
	}

	/**
	 * @return string
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getModuleId()
	{
		return $this->options->getModuleId();
	}

	/**
	 * @param $filter
	 * @return Input|null
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function search(array $filter)
	{
		foreach ($this->inputs as $input) {
			/**
			 * @var Input $input
			 */
			if (isset($filter['id']) && strlen($filter['id'])
				&& $filter['id'] == $input->getValueId())
				return $input;

			if (isset($filter['name']) && strlen($filter['name'])
				&& $filter['name'] == $input->getValueName())
				return $input;
		}

		return null;
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function __clone()
	{
		$newInputs = [];

		foreach ($this->inputs as $input) {
			/** @var Input $input */
			/** @var Input $newInput */
			$newInput = clone $input;
			$newInput->setTab($this);
			$newInputs[] = $newInput;
		}

		$this->inputs = $newInputs;
	}

	/**
	 * @param $inputName
	 * @return Input|null
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getValue($inputName)
	{
		$input = $this->searchByName($inputName);

		if ($input instanceof Input)
			return $input->getValue();

		return null;
	}

	/**
	 * @param $name
	 * @return null
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function searchByName($name)
	{
		$filter = [
			'name' => Options::getParam($name, $this->getPresetId(), $this->getSiteId())
		];

		return $this->search($filter);
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function clear()
	{
		foreach ($this->inputs as $input)
			/**
			 * @var Input $input
			 */
			$input->removeValue();
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getName()
	{
		return Options::getParam($this->name, $this->presetId, $this->siteId);
	}

	/**
	 * @param Input $input
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function addInput(Input $input)
	{
		$this->inputs[] = $input;
	}

	/**
	 * @param array $input
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function addInputArray(array $input)
	{
		$this->inputs[] = Input::factory($input, $this);
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function show()
	{
		foreach ($this->inputs as $input)
			/**
			 * @var Input $input
			 */
			$input->show();
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function setValuesFromRequest()
	{
		foreach ($this->inputs as $input)
			/**
			 * @var Input $input
			 */
			$input->setValueFromRequest();
	}

	/**
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getInfo()
	{
		$name           = $this->getName();
		$icon           = "ib_settings";
		$label          = strlen($this->siteId)
			? $this->label . ' [' . $this->siteId . ']'
			: $this->label;
		$description    = strlen($this->siteId)
			? $this->description . ' [' . $this->siteId . ']'
			: $this->description;

		$params = array_merge(['tab' => $this],
			compact('name', 'icon', 'label', 'description'));

		$this->options->runEvent(Options::EVENT__BEFORE_GET_TAB_INFO, $params);

		return [
			'DIV'   => $params['name'],
			'TAB'   => $params['label'],
			'ICON'  => $params['icon'],
			'TITLE' => $params['description']
		];
	}

	/**
	 * @return null
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getPresetName()
	{
		if (!$this->isPreset()
			|| !$this->getPresetId())
			return null;

		$preset = Presets::getById($this->getPresetId(),
			$this->getModuleId(), $this->siteId);

		if (is_array($preset) && isset($preset['name']))
			return $preset['name'];

		return null;
	}

	/**
	 * @param            $name
	 * @param            $value
	 * @return bool
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function setInputValue($name, $value)
	{
		$input = $this->searchByName($name);

		if (!$input instanceof Input)
			return false;

		$input->setValue($value);

		return true;
	}
}