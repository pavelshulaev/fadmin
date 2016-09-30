<?php
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class rover_fadmin extends CModule
{
    public $MODULE_ID	= "rover.fadmin";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS = "Y";
    public $PARTNER_NAME;
    public $PARTNER_URI;

    protected $errors = [];

    /**
     *
     */
    function __construct()
    {
		$arModuleVersion = [];

        require(__DIR__ . "/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
			$this->MODULE_VERSION		= $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE	= $arModuleVersion["VERSION_DATE"];
        } else {
            $this->errors[] = Loc::getMessage('rover_fa__version_info_error');
		}

        $this->MODULE_NAME			= Loc::getMessage("rover_fa__name");
        $this->MODULE_DESCRIPTION	= Loc::getMessage("rover_fa__descr");
        $this->PARTNER_NAME         = Loc::getMessage("PARTNER_NAME");
        $this->PARTNER_URI          = Loc::getMessage("PARTNER_URI");
	}

    /**
     * @author Shulaev (pavel.shulaev@gmail.com)
     */
    public function DoInstall()
    {
        global $APPLICATION;
        $rights = $APPLICATION->GetGroupRight($this->MODULE_ID);

        if ($rights == "W")
            $this->ProcessInstall();
	}

    /**
     * @author Shulaev (pavel.shulaev@gmail.com)
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
     * @author Shulaev (pavel.shulaev@gmail.com)
     */
    public function GetModuleRightsList()
    {
        return array(
            "reference_id" => ["D", "R", "W"],
            "reference" => [
                Loc::getMessage('rover_fa__reference_deny'),
                Loc::getMessage('rover_fa__reference_read'),
                Loc::getMessage('rover_fa__reference_write')
            ]
        );
    }

	/**
	 * Инсталляция файлов и зависимотей, регистрация модуля
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	private function ProcessInstall()
    {
        global $APPLICATION, $errors;

        if (empty($this->errors))
            ModuleManager::registerModule($this->MODULE_ID);

        $errors = $this->errors;
	    $APPLICATION->IncludeAdminFile(Loc::getMessage("rover_fa__install_title"), $_SERVER['DOCUMENT_ROOT'] . getLocalPath("modules/". $this->MODULE_ID ."/install/message.php"));
    }

	/**
	 * Удаление файлов и зависимостей. Снятие модуля с регистрации
	 * @author Shulaev (pavel.shulaev@gmail.com)
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
