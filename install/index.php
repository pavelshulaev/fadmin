<?php
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class rover_fadmin extends CModule
{
    var $MODULE_ID	= "rover.fadmin";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";
    var $PARTNER_NAME;
    var $PARTNER_URI;

    protected $errors = array();

    function __construct()
    {
		$arModuleVersion = array();

        require(__DIR__ . "/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
			$this->MODULE_VERSION		= $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE	= $arModuleVersion["VERSION_DATE"];
        } else {
            $this->errors[] = Loc::getMessage('rover_fa__version_info_error');
		}

        $this->MODULE_NAME			= Loc::getMessage("rover_fa__name");
        $this->MODULE_DESCRIPTION	= Loc::getMessage("rover_fa__descr");
        $this->PARTNER_NAME         = GetMessage("rover_fa__partner_name");
        $this->PARTNER_URI          = GetMessage("rover_fa__partner_uri");
	}

    /**
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function DoInstall()
    {
        global $APPLICATION;
        $rights = $APPLICATION->GetGroupRight($this->MODULE_ID);

        if ($rights == "W")
            $this->ProcessInstall();
	}

    /**
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function DoUninstall()
    {
        global $APPLICATION;
        $rights = $APPLICATION->GetGroupRight($this->MODULE_ID);

        if ($rights == "W")
            $this->ProcessUninstall();
    }

    /**
     * @return array
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function GetModuleRightsList()
    {
        return array(
            "reference_id" => array("D", "R", "W"),
            "reference" => array(
                Loc::getMessage('rover_fa__reference_deny'),
                Loc::getMessage('rover_fa__reference_read'),
                Loc::getMessage('rover_fa__reference_write')
            )
        );
    }

	/**
	 * »нсталл€ци€ файлов и зависимотей, регистраци€ модул€
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	private function ProcessInstall()
    {
        global $APPLICATION, $errors;

        if (PHP_VERSION_ID < 50400)
            $this->errors[] = Loc::getMessage('rover_fa__php_version_error');

        if (empty($this->errors))
            ModuleManager::registerModule($this->MODULE_ID);

        $errors = $this->errors;
	    $APPLICATION->IncludeAdminFile(Loc::getMessage("rover_fa__install_title"), $_SERVER['DOCUMENT_ROOT'] . getLocalPath("modules/". $this->MODULE_ID ."/install/message.php"));
    }

	/**
	 * ”даление файлов и зависимостей. —н€тие модул€ с регистрации
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	private function ProcessUninstall()
	{
        global $APPLICATION, $errors;

        if (empty($this->errors))
            ModuleManager::unRegisterModule($this->MODULE_ID);

        $errors = $this->errors;
        $APPLICATION->IncludeAdminFile(Loc::getMessage("rover_fa__uninstall_title"), $_SERVER['DOCUMENT_ROOT'] . getLocalPath("modules/". $this->MODULE_ID ."/install/unMessage.php"));
	}
}
