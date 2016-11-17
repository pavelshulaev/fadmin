<?php
/**
 * This is a demo of module options file
 */
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\Loader;
use \Rover\Fadmin\Admin\Panel;
/**
 * Name of your child from \Rover\Fadmin\Options
 */
use \Rover\Fadmin\TestOptions;

if (!Loader::includeModule($mid)
	|| !Loader::includeModule('rover.fadmin'))
	throw new SystemException('module "' . $mid . '" not found!');

Loc::loadMessages(__FILE__);
Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/options.php");

(new Panel(TestOptions::getInstance()))->show();