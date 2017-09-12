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
use Rover\Fadmin\Tab;

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
     * Panel constructor.
     *
     * @param Options $options
     * @param array   $formParams
     */
	public function __construct(Options $options, array $formParams = [])
	{
		global $Update, $Apply, $RestoreDefaults, $REQUEST_METHOD;

		$this->options = $options;

		$tabControl = $this->getTabControl();

		$this->request  = new Request($options, $REQUEST_METHOD, $Update, $Apply, $RestoreDefaults, $tabControl->ActiveTabParam());
		$this->form     = new Form($tabControl, $options, $formName);
	}

	/**
	 * @return \CAdminTabControl
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function getTabControl()
	{
		if (is_null($this->tabControl)) {
			$allTabsInfo      = $this->getAllTabsInfo();
			$this->tabControl = new \CAdminTabControl("tabControl", $allTabsInfo);
		}

		return $this->tabControl;
	}

    /**
     * @return array
     * @author Pavel Shulaev (https://rover-it.me)
     */
	protected function getAllTabsInfo()
    {
        $tabs           = $this->options->tabMap->getTabs();
        $allTabsInfo    = [];

        foreach ($tabs as $tab)
            /**
             * @var Tab $tab
             */
            $allTabsInfo[] = $this->getTabInfo($tab);

        // add group rights tab
        if ($this->options->settings->getGroupRights())
            $allTabsInfo[] = [
                "DIV"   => "edit2",
                "TAB"   => GetMessage("MAIN_TAB_RIGHTS"),
                "ICON"  => "form_settings",
                "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")
            ];

        return $allTabsInfo;
    }

    /**
     * @param Tab $tab
     * @return array
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getTabInfo(Tab $tab)
    {
        $name           = $tab->getName();
        $icon           = "ib_settings";
        $label          = strlen($tab->getSiteId())
            ? $tab->getLabel() . ' [' . $tab->getSiteId() . ']'
            : $tab->getLabel();
        $description    = strlen($tab->getSiteId())
            ? $tab->getDescription() . ' [' . $tab->getSiteId() . ']'
            : $tab->getDescription();

        $params = array_merge(['tab' => $tab],
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
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function show()
	{
		$this->request->get();
		$this->options->message->show();
		$this->form->show();
	}
}