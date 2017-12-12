<?php
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

/**
 * Class rover_fadmin
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */
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

    /**
     * rover_fadmin constructor.
     */
    function __construct()
    {
        global $fadminErrors;
        
		$arModuleVersion    = array();
        $fadminErrors       = array();

        require(__DIR__ . "/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
			$this->MODULE_VERSION		= $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE	= $arModuleVersion["VERSION_DATE"];
        } else {
            $fadminErrors[] = Loc::getMessage('rover_fa__version_info_error');
		}

        $this->MODULE_NAME			= Loc::getMessage("rover_fa__name");
        $this->MODULE_DESCRIPTION	= Loc::getMessage("rover_fa__descr");
        $this->PARTNER_NAME         = GetMessage("rover_fa__partner_name");
        $this->PARTNER_URI          = GetMessage("rover_fa__partner_uri");
	}

    /**
     * @throws \Bitrix\Main\IO\FileNotFoundException
     * @author Pavel Shulaev (https://rover-it.me)
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
     * @throws \Bitrix\Main\IO\FileNotFoundException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	private function ProcessInstall()
    {
        global $fadminErrors;

        if (PHP_VERSION_ID < 50306)
            $fadminErrors[] = Loc::getMessage('rover_fa__php_version_error');

        $this->copyFiles();

        global $APPLICATION, $fadminErrors;

        if (empty($fadminErrors))
            ModuleManager::registerModule($this->MODULE_ID);

	    $APPLICATION->IncludeAdminFile(Loc::getMessage("rover_fa__install_title"),
            dirname(__FILE__) . "/message.php");
    }

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	private function ProcessUninstall()
	{
        $this->removeFiles();
        //if (empty($fadminErrors))
        // uninstall anywhere
        ModuleManager::unRegisterModule($this->MODULE_ID);

        global $APPLICATION;

        $APPLICATION->IncludeAdminFile(Loc::getMessage("rover_fa__uninstall_title"),
            dirname(__FILE__) . "/unMessage.php");
	}

    /**
     * @param $fromDir
     * @param $toDir
     * @throws \Bitrix\Main\IO\FileNotFoundException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    private function copyDir($fromDir, $toDir)
    {
        global $fadminErrors;

        $dir = $this->checkDir($toDir);

        if (!is_writable($dir->getPhysicalPath())){
            $fadminErrors[] = Loc::getMessage('rover_fa__ERROR_PERMISSIONS', array('#path#' => $dir->getPhysicalPath()));
            return;
        }

        $fromDir = getLocalPath("modules/". $this->MODULE_ID . $fromDir);

        if (!\CopyDirFiles(
            Application::getDocumentRoot() . $fromDir,
            Application::getDocumentRoot() . $toDir,
            TRUE,
            TRUE))
        {
            $fadminErrors[] = Loc::getMessage('rover_fa__ERROR_COPY_FILES',
                array('#pathFrom#' => $fromDir, '#toPath#' => $toDir));
        }
    }

    /**
     * @throws \Bitrix\Main\IO\FileNotFoundException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    private function copyFiles()
    {
        $this->copyDir('/install/js/', '/bitrix/js/' . $this->MODULE_ID . '/');
        $this->copyDir('/install/css/', '/bitrix/css/' . $this->MODULE_ID . '/');
    }

    /**
     * @author Pavel Shulaev (http://rover-it.me)
     */
    private function removeFiles()
    {
       $this->deleteDir('/bitrix/js/' . $this->MODULE_ID . '/');
       $this->deleteDir('/bitrix/css/' . $this->MODULE_ID . '/');
    }

    /**
     * @param $dirName
     * @author Pavel Shulaev (http://rover-it.me)
     */
    private function deleteDir($dirName)
    {
        global $fadminErrors;

        $dirName = str_replace(array('//', '///'), '/', Application::getDocumentRoot() . '/' . $dirName);

        if (!is_writable($dirName)){
            $fadminErrors[] = Loc::getMessage('rover_fa__ERROR_PERMISSIONS', array('#path#' => $dirName));
            return;
        }

        Directory::deleteDirectory($dirName);
    }

    /**
     * @param $path
     * @return Directory
     * @throws \Bitrix\Main\IO\FileNotFoundException
     * @author Pavel Shulaev (http://rover-it.me)
     */
    private function checkDir($path)
    {
        $path   = Application::getDocumentRoot() . $path;

        $dir    = Directory::isDirectoryExists($path)
            ? new Directory($path)
            : Directory::createDirectory($path);

        $dir->markWritable();

        return $dir;
    }
}
