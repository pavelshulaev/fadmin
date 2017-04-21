<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:43
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Admin;

use \Rover\Fadmin\Options;

/**
 * Class Panel
 *
 * @package Rover\Fadmin\Admin
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Panel
{
	protected $options;
	protected $request;
	protected $form;
	protected $tabControl;

	/**
	 * @param Options $options
	 * @param null    $formName
	 */
	public function __construct(Options $options, $formName = null)
	{
		global $Update, $Apply, $RestoreDefaults, $REQUEST_METHOD;

		$this->options = $options;

		$tabControl = $this->getTabControl();

		$this->request  = new Request($tabControl, $options, $REQUEST_METHOD, $Update, $Apply, $RestoreDefaults);
		$this->form     = new Form($tabControl, $options, $formName);
	}

	/**
	 * @return \CAdminTabControl
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function getTabControl()
	{
		if (is_null($this->tabControl)) {
			$allTabsInfo      = $this->options->getAllTabsInfo();
			$this->tabControl = new \CAdminTabControl("tabControl", $allTabsInfo);
		}

		return $this->tabControl;
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function show()
	{
		$this->request->get();
		$this->options->message->show();
		$this->form->show();
	}
}