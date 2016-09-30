<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 22.01.2016
 * Time: 20:17
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Admin;

use Bitrix\Main\Application;
use Bitrix\Main\DB\Exception;
use Bitrix\Main\Request as BxRequest;
use Rover\Fadmin\Inputs\Addpreset;
use Rover\Fadmin\Inputs\Removepreset;
use Rover\Fadmin\Options;
use Rover\Fadmin\Presets;
use Rover\Fadmin\Tab;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\ArgumentNullException;

class Request
{
	protected $moduleId;
	protected $options;
	protected $tabControl;

	protected $requestMethod = 'POST';
	protected $update;
	protected $apply;
	protected $restoreDefaults;

	/**
	 * @param \CAdminTabControl $tabControl
	 * @param Options           $options
	 * @param                   $requestMethod
	 * @param                   $update
	 * @param                   $apply
	 * @param                   $restoreDefaults
	 */
	public function __construct(\CAdminTabControl $tabControl, Options $options, $requestMethod, $update, $apply, $restoreDefaults)
	{
		$this->tabControl   = $tabControl;
		$this->options      = $options;
		$this->moduleId     = htmlspecialcharsbx($this->options->getModuleId());

		if (!is_null($requestMethod))
			$this->requestMethod = htmlspecialcharsbx($requestMethod);

		$this->update           = (bool)$update;
		$this->apply            = (bool)$apply;
		$this->restoreDefaults  = (bool)$restoreDefaults;
	}

	/**
	 * @throws \Bitrix\Main\ArgumentNullException
	 * @throws \Bitrix\Main\SystemException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function get()
	{
		$this->getRequest();
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function redirect()
	{
		$this->options->runEvent(Options::EVENT__AFTER_GET_REQUEST);

		if(strlen($this->update)>0
			&& strlen($_REQUEST["back_url_settings"])>0)
		{
			LocalRedirect($_REQUEST["back_url_settings"]);
		} else {
			global $APPLICATION;
			LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($this->moduleId)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$this->tabControl->ActiveTabParam());
		}
	}

	/**
	 * @return \Bitrix\Main\HttpRequest|void
	 * @throws \Bitrix\Main\ArgumentNullException
	 * @throws \Bitrix\Main\SystemException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function getRequest()
	{
		// action before
		if(false === $this->options->runEvent(Options::EVENT__BEFORE_GET_REQUEST))
			return;

		$request = Application::getInstance()
			->getContext()
			->getRequest();

		if ($request->get(Addpreset::$type)) {

			$this->addPreset($request);

		} elseif ($request->get(Removepreset::$type)) {

			$this->removePreset($request);

		} else {

			if (!$this->check())
				return;

			if(strlen($this->restoreDefaults) > 0)
				$this->restoreDefaults();
			else
				try {
					$this->addRequest();
				} catch (\Exception $e) {
					$this->options->addMessage($e->getMessage(), 'ERROR');
				}
		}
	}

	/**
	 * @param BxRequest $request
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function addPreset(BxRequest $request)
	{
		list($siteId, $value) = explode(Addpreset::SEPARATOR,
			$request->get(Addpreset::$type));

		// action beforeAddPreset
		$params = compact('siteId', 'value');
		if (false === $this->options->runEvent(Options::EVENT__BEFORE_ADD_PRESET, $params))
			return;

		if (!isset($params['name']))
			$params['name'] = $params['value'];

		$params['id'] = Presets::add(
			$this->moduleId,
			$params['name'],
			$params['siteId']
		);

		// action afterAddPreset
		$this->options->runEvent(Options::EVENT__AFTER_ADD_PRESET, $params);

		$this->redirect();
	}

	/**
	 * @param BxRequest $request
	 * @throws \Bitrix\Main\ArgumentNullException
	 * @throws \Bitrix\Main\SystemException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function removePreset(BxRequest $request)
	{
		list($siteId, $id) = explode(Removepreset::SEPARATOR,
			$request->get(Removepreset::$type));

		if (!$id)
			throw new \Bitrix\Main\ArgumentNullException('id');

		// action beforeAddPreset
		if(false === $this->options->runEvent(
			Options::EVENT__BEFORE_REMOVE_PRESET,
			compact('siteId', 'id')))
			return;

		$presetTab = $this->options->getTabByPresetId($id, $siteId);

		if ($presetTab instanceof Tab === false)
			throw new \Bitrix\Main\SystemException('presetTab is not an Tab instance');

		$presetTab->clear();

		Presets::remove($this->moduleId, $id, $siteId);

		// action afterRemovePreset
		$this->options->runEvent(Options::EVENT__AFTER_REMOVE_PRESET,
			compact('siteId'));

		$this->redirect();
	}

	/**
	 * @return bool
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function check()
	{
		return (($this->requestMethod === 'POST')
			&& (strlen($this->update.$this->apply.$this->restoreDefaults) > 0)
			&& check_bitrix_sessid());
	}

	/**
	 * @throws \Bitrix\Main\ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function restoreDefaults()
	{
		Option::delete($this->moduleId);
		$this->redirect();
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function addRequest()
	{
		if(false === $this->options->runEvent(Options::EVENT__BEFORE_ADD_VALUES_FROM_REQUEST))
			return;

		$tabs = $this->options->getTabs();

		foreach ($tabs as $tab)
			/**
			 * @var Tab $tab
			 */
			$tab->setValuesFromRequest();

		$this->options->runEvent(Options::EVENT__AFTER_ADD_VALUES_FROM_REQUEST);

		$this->redirect();
	}
}