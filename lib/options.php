<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 08.01.2016
 * Time: 18:35
 *
 * @author Shulaev (pavel.shulaev@gmail.com)
 */

namespace Rover\Fadmin;

use Bitrix\Main\Localization\Loc;

use \Bitrix\Main;
use \Bitrix\Main\ArgumentNullException;
use Rover\Fadmin\Inputs\Input;

Loc::LoadMessages(__FILE__);
/**
 * Interface template
 * Абстрактный класс шаблона опций.
 * Должен быть унаследован классом, который занимается работой с опциями и рисованием интерфейса
 *
 *@package Rover\Fadmin
 * @author  Shulaev (pavel.shulaev@gmail.com)
 */
abstract class Options
{
	/**
	 * events
	 */
	const EVENT__BEFORE_REQUEST         = 'beforeRequest';
	const EVENT__AFTER_REQUEST          = 'afterRequest';
	const EVENT__BEFORE_ADD_REQUEST     = 'beforeAddRequest';
	const EVENT__AFTER_ADD_REQUEST      = 'afterAddRequest';
	const EVENT__BEFORE_ADD_PRESET      = 'beforeAddPreset';
	const EVENT__AFTER_ADD_PRESET       = 'afterAddPreset';
	const EVENT__BEFORE_REMOVE_PRESET   = 'beforeRemovePreset';
	const EVENT__AFTER_REMOVE_PRESET    = 'afterRemovePreset';
	const EVENT__BEFORE_MAKE_PRESET_TAB = 'beforeMakePresetTab';
	const EVENT__AFTER_MAKE_PRESET_TAB  = 'afterMakePresetTab';
	const EVENT__BEFORE_SHOW_TAB        = 'beforeShowTab';
	const EVENT__AFTER_GET_TABS         = 'afterGetTabs';
	const EVENT__BEFORE_GET_TAB_INFO    = 'beforeGetTabInfo';

	const SEPARATOR = '__';

	const SETTINGS__CHECKBOX_BOOLEAN = 'checkbox_boolean';
	/**
	 * current module id
	 * @var string
	 */
	protected $moduleId;

	/**
	 * tabs helper
	 * @var TabMap
	 */
	protected $tabMap;

	/**
	 * service messages
	 * @var array
	 */
	protected $messages = [];

	/**
	 * options values cache
	 * @var array
	 */
	protected $cache = [];

	/**
	 * default settings
	 * @var array
	 */
	protected $settings = [
		self::SETTINGS__CHECKBOX_BOOLEAN => false,
	];

	/**
	 * unique instance for each module
	 * @var array
	 */
	protected static $instances = [];

	/**
	 * @param $moduleId
	 * @throws Main\ArgumentNullException
	 */
	public function __construct($moduleId)
	{
		if (!strlen($moduleId))
			throw new ArgumentNullException('moduleId');

		$this->moduleId = $moduleId;
		$this->tabMap   = new TabMap($this);

		// method must be in child
		$config = $this->getConfig();

		/// adding settings
		if (isset($config['settings']))
			$this->settings = array_merge($this->settings, $config['settings']);

		// tabs
		if (!isset($config['tabs']))
			throw new ArgumentNullException('tabs');

		$this->addTabs($config['tabs']);
	}

	/**
	 * @return array
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function getSettings()
	{
		return $this->settings;
	}

	/**
	 * for singleton
	 * @param $moduleId
	 * @return static
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public static function getInstance($moduleId)
	{
		if (!isset(self::$instances[$moduleId]))
			self::$instances[$moduleId] = new static($moduleId);

		return self::$instances[$moduleId];
	}


	/**
	 * @return array
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function getAllTabsInfo()
	{
		return $this->tabMap->getAllTabsInfo();
	}

	/**
	 * @param $name
	 * @param $params
	 * @return mixed
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function runEvent($name, &$params = [])
	{
		if (!method_exists($this, $name))
			return true;

		try{
			return $this->$name($params);
		} catch (\Exception $e) {
			$this->addMessage($e->getMessage(), 'ERROR');
			return false;
		}
	}

	/**
	 * returns config array. Now it's contained only 'tabs' section
	 * @return mixed
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	abstract public function getConfig();

	/**
	 * @return mixed
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function getModuleId()
	{
		return $this->moduleId;
	}

	/**
	 * generate param string
	 * @param        $param
	 * @param string $presetId
	 * @param string $siteId
	 * @return string
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public static function getParam($param, $presetId = '', $siteId = '')
	{
		if (strlen($presetId))
			$param = htmlspecialcharsbx($presetId) . self::SEPARATOR . $param;

		if (strlen($siteId))
			$param = htmlspecialcharsbx($siteId) . self::SEPARATOR . $param;

		return $param;
	}

	/**
	 * @param        $message
	 * @param string $type
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function addMessage($message, $type = 'OK')
	{
		$this->messages[] = [
			'MESSAGE'   => htmlspecialcharsbx($message),
			'TYPE'      => htmlspecialcharsbx($type),
		];
	}

	/**
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function showMessages()
	{
		foreach ($this->messages as $message)
			\CAdminMessage::ShowMessage($message);
	}

	/**
	 * Generate tabs by tabs config array
	 * @param $tabsParams
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	protected function addTabs($tabsParams)
	{
		foreach ($tabsParams as $tabParams){
			if (empty($tabParams))
				continue;

			$tab = Tab::factory($tabParams, $this);
			$this->addTab($tab);
		}
	}

	/**
	 * adding tab
	 * clone preset tab for all presets
	 * @param Tab $tab
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	protected function addTab(Tab $tab)
	{
		$this->tabMap->addTab($tab);
	}

	/**
	 * @return array
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function getTabs()
	{
		$tabs = $this->tabMap->getTabs();
		$this->runEvent(self::EVENT__AFTER_GET_TABS, compact('tabs'));

		return $tabs;
	}

	/**
	 * returns value from preset
	 * @param            $inputName
	 * @param            $presetId
	 * @param string     $siteId
	 * @param bool|false $reload
	 * @return mixed|null
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function getPresetValue($inputName, $presetId, $siteId = '', $reload = false)
	{
		return $this->getValue($inputName, $presetId, $siteId, $reload);
	}

	/**
	 * returns value by name
	 * @param            $inputName
	 * @param string     $siteId
	 * @param bool|false $reload
	 * @return mixed|null
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function getNormalValue($inputName, $siteId = '', $reload = false)
	{
		return $this->getValue($inputName, '', $siteId, $reload);
	}

	/**
	 * search input in tabs by name
	 * @param        $inputName
	 * @param string $presetId
	 * @param string $siteId
	 * @return null|Input
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	protected function getInputByName($inputName, $presetId = '', $siteId = '')
	{
		$tabs = $this->getTabs();

		foreach ($tabs as $tab) {
			/**
			 * @var Tab $tab
			 */
			if (($presetId && $tab->getPresetId() != $presetId)
				|| ($siteId && $tab->getSiteId() != $siteId))
				continue;

			$input = $tab->searchByName($inputName);

			if ($input instanceof Input)
				return $input;
		}

		return null;
	}

	/**
	 * @param            $inputName
	 * @param string     $presetId
	 * @param string     $siteId
	 * @param bool|false $reload
	 * @return mixed
	 * @throws Main\SystemException
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function getValue($inputName, $presetId = '', $siteId = '', $reload = false)
	{
		$key = md5($inputName . $presetId . $siteId);

		if (!isset($this->cache[$key]) || $reload) {

			$input = $this->getInputByName($inputName, $presetId, $siteId);

			if ($input instanceof Input)
				$this->cache[$key] = $input->getValue();
			else
				throw new Main\SystemException('input not found');
		}

		return $this->cache[$key];
	}

	/**
	 * @param        $inputName
	 * @param string $presetId
	 * @param string $siteId
	 * @return mixed
	 * @throws Main\SystemException
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function getDefaultValue($inputName, $presetId = '', $siteId = '')
	{
		$input = $this->getInputByName($inputName, $presetId, $siteId);

		if ($input instanceof Input)
			return $input->getDefault();

		throw new Main\SystemException('input not found');
	}

	/**
	 * @param        $presetId
	 * @param string $siteId
	 * @return mixed
	 * @throws Main\SystemException
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function getTabByPresetId($presetId, $siteId = '')
	{
		if (!$presetId)
			throw new Main\ArgumentNullException('presetId');

		return $this->tabMap->getTabByPresetId($presetId, $siteId);
	}

	/**
	 * @param string $siteId
	 * @return array
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function getPresetsIds($siteId = '')
	{
		return Presets::getIds($this->moduleId, $siteId);
	}
}