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
use Bitrix\Main\Request as BxRequest;
use Rover\Fadmin\Inputs\Addpreset;
use Rover\Fadmin\Inputs\Removepreset;
use Rover\Fadmin\Options;
use Rover\Fadmin\Tab;
use \Bitrix\Main\Config\Option;
/**
 * Class Request
 *
 * @package Rover\Fadmin\Admin
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Request
{
    /**
     * @var mixed|string
     */
	protected $moduleId;

    /**
     * @var Options
     */
	protected $options;

    /**
     * @var string
     */
	protected $defaultActiveTab;

    /**
     * @var mixed|string
     */
	protected $requestMethod = 'POST';

    /**
     * @var bool
     */
	protected $update;

    /**
     * @var bool
     */
	protected $apply;

    /**
     * @var bool
     */
	protected $restoreDefaults;

    /**
     * Request constructor.
     *
     * @param Options $options
     * @param         $requestMethod
     * @param         $update
     * @param         $apply
     * @param         $restoreDefaults
     * @param null    $defaultActiveTab
     */
	public function __construct(Options $options, $requestMethod, $update, $apply, $restoreDefaults, $defaultActiveTab = null)
	{
		$this->defaultActiveTab = trim($defaultActiveTab);
		$this->options          = $options;
		$this->moduleId         = htmlspecialcharsbx($this->options->getModuleId());

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

		} elseif ($this->check()){

			if(strlen($this->restoreDefaults) > 0)
				$this->restoreDefaults();
			else
				try {
					$this->addRequest();
				} catch (\Exception $e) {
					$this->options->message->addError($e->getMessage());
				}
		}
	}

	/**
	 * @param null $activeTab
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function redirect($activeTab = null)
	{
		if (false === $this->options->runEvent(Options::EVENT__BEFORE_REDIRECT_AFTER_REQUEST))
			return;

		$request = Application::getInstance()->getContext()->getRequest();

		if (strlen($this->update) && strlen($request["back_url_settings"]))
			LocalRedirect($request["back_url_settings"]);

        $activeTab = $activeTab
            ? 'tabControl_active_tab=' . $activeTab
            : $this->defaultActiveTab;

        global $APPLICATION;
        LocalRedirect($APPLICATION->GetCurPage()
            . "?mid=" . urlencode($this->moduleId)
            . "&lang=" . urlencode(LANGUAGE_ID)
            . "&back_url_settings=" . urlencode($request["back_url_settings"])
            . "&" . $activeTab);
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

		$params['id'] = $this->options->preset->add(
			$params['name'],
			$params['siteId']
		);

		//reload tabs
		$this->options->tabMap->reloadTabs();
		$this->options->runEvent(Options::EVENT__AFTER_ADD_PRESET, $params);

		$presetTabName = $this->options->tabMap->getTabByPresetId($params['id'], $params['siteId'], true);

		if ($presetTabName instanceof Tab)
		    $this->redirect($presetTabName->getName());
        else
            $this->options->message->addError('added preset not found');
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

		// action beforeRemovePreset
		if(false === $this->options->runEvent(
			Options::EVENT__BEFORE_REMOVE_PRESET,
			compact('siteId', 'id')))
			return;

		/**
		 * @var Tab $presetTab
		 */
		$presetTab = $this->options->tabMap->getTabByPresetId($id, $siteId);

		if ($presetTab instanceof Tab === false)
			throw new \Bitrix\Main\SystemException('presetTab is not an Tab instance');

		$presetTab->clear();

		$this->options->preset->remove($id, $siteId);

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

		$tabs = $this->options->tabMap->getTabs();

		foreach ($tabs as $tab) {
			/**
			 * @var Tab $tab
			 */
			if(false === $this->options->runEvent(
					Options::EVENT__BEFORE_ADD_VALUES_TO_TAB_FROM_REQUEST,
					compact('tab')))
				continue;

			$tab->setValuesFromRequest();
		}

        if(false === $this->options->runEvent(
		    Options::EVENT__AFTER_ADD_VALUES_FROM_REQUEST,
            compact('tabs')))
            return;

		$this->redirect();
	}
}