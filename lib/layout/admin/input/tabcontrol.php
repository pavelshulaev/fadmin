<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 19.06.2018
 * Time: 9:02
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Rover\Fadmin\Inputs\Tab;
use Rover\Fadmin\Layout\Admin\Input;
use Rover\Fadmin\Layout\Form;
use Rover\Fadmin\Options\Event;
use \Rover\Fadmin\Inputs\Input as InputEngine;

/**
 * Class Tabcontrol
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Tabcontrol extends Input
{
    /** @var \CAdminTabControl */
    protected $bxTabControl;

    /**
     * Tabcontrol constructor.
     *
     * @param InputEngine $input
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\SystemException
     */
    public function __construct(InputEngine $input)
    {
        parent::__construct($input);

        $this->bxTabControl = new \CAdminTabControl("tabControl", $this->getAllTabsInfo());
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw()
    {
        $this->showInput();
    }

    /**
     * @return \CAdminTabControl
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getBxTabControl()
    {
        return $this->bxTabControl;
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        if (!$this->input instanceof \Rover\Fadmin\Inputs\Tabcontrol)
            return;

        if ($this->input->getOptionsEngine()->settings->getUseSort())
            $this->input->sort();

        // showing tabs
        $tabs = $this->input->getAdminTabs();

        if (!count($tabs))
            return;

        $this->bxTabControl->Begin();
        $this->showFormBegin();

        foreach ($tabs as $tab)
            $this->showTab($tab);

        if ($this->input->getOptionsEngine()->settings->getGroupRights())
            $this->showGroupRightsTab();

        $this->bxTabControl->EndTab();

        $this->showFormEnd();
    }

    /**
     * @return array
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getAllTabsInfo()
    {
        $tabs           = $this->input->getAdminTabs(true);
        $allTabsInfo    = array();

        foreach ($tabs as $tab)
            /** @var Tab $tab */
            $allTabsInfo[] = $this->getTabInfo($tab);

        // add group rights tab
        if ($this->input->getOptionsEngine()->settings->getGroupRights())
            $allTabsInfo[] = array(
                "DIV"   => "edit2",
                "TAB"   => Loc::getMessage("MAIN_TAB_RIGHTS"),
                "ICON"  => "form_settings",
                "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS")
            );

        return $allTabsInfo;
    }

    /**
     * @author Pavel Shulaev (http://rover-it.me)
     */
    protected function showFormBegin()
    {
        global $APPLICATION;

        ?><form method="post"
                id="fadmin-form"
                enctype="multipart/form-data"
                name='<?=$this->input->getModuleId()?>'
                action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($this->input->getModuleId())?>&amp;lang=<?=LANGUAGE_ID?>"><?php
    }

    /**
     * @param Tab $tab
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function showTab(Tab $tab)
    {
        // action afterRemovePreset
        if (!$this->input->getOptionsEngine()->event
            ->handle(Event::BEFORE_SHOW_TAB, compact('tab'))
            ->isSuccess())
            return;

        $this->bxTabControl->BeginNextTab();

        self::drawStatic($tab);
    }

    /**
     * @param Tab $tab
     * @return array
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getTabInfo(Tab $tab)
    {
        $name   = $tab->getFieldName();
        $icon   = "ib_settings";
        $label  = strlen($tab->getSiteId())
            ? $tab->getLabel() . ' [' . $tab->getSiteId() . ']'
            : $tab->getLabel();

        /** @deprecated $description */
        $default = $description = strlen($tab->getSiteId())
            ? $tab->getDefault() . ' [' . $tab->getSiteId() . ']'
            : $tab->getDefault();

        $params = $this->input->getOptionsEngine()->event
            ->handle(Event::BEFORE_GET_TAB_INFO,
                compact('tab', 'name', 'icon', 'label', 'description', 'default'))
            ->getParameters();

        return array(
            'DIV'   => $params['name'],
            'TAB'   => $params['label'],
            'ICON'  => $params['icon'],
            'TITLE' => $params['description'] != $description
                ? $params['description']
                : $params['default']
        );
    }


    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function showGroupRightsTab()
    {
        $this->bxTabControl->BeginNextTab();

        Form::includeGroupRightsTab();
    }

    /**
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function showFormEnd()
    {
        $request = Application::getInstance()
            ->getContext()
            ->getRequest();

        $backUrl = $request->get('back_url_settings');

        $this->bxTabControl->Buttons();
        ?><input
        type="submit"
        name="Update"
        value="<?=Loc::getMessage("MAIN_SAVE")?>"
        title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE")?>"
        class="adm-btn-save">
        <input
            type="submit"
            name="Apply"
            value="<?=Loc::getMessage("MAIN_OPT_APPLY")?>"
            title="<?=Loc::getMessage("MAIN_OPT_APPLY_TITLE")?>">
        <?php if(strlen($backUrl) > 0):?>
        <input
            type="button"
            name="Cancel"
            value="<?=Loc::getMessage("MAIN_OPT_CANCEL")?>"
            title="<?=Loc::getMessage("MAIN_OPT_CANCEL_TITLE")?>"
            onclick="window.location='<?=htmlspecialcharsbx(\CUtil::addslashes($backUrl))?>'">
        <input
            type="hidden"
            name="back_url_settings"
            value="<?=htmlspecialcharsbx($backUrl)?>">
    <?php endif?>
        <input
            type="submit"
            name="RestoreDefaults"
            title="<?=Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS")?>"
            OnClick="return confirm('<?=AddSlashes(Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')"
            value="<?=Loc::getMessage("MAIN_RESTORE_DEFAULTS")?>">
        <?=bitrix_sessid_post();?>
        <?php $this->bxTabControl->End();?>
        </form><?php
    }
}