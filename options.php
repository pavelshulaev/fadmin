<?php
/**
 * This is a demo of module options file
 */
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\Loader;
use \Rover\Fadmin\Layout\Admin\Form;
/**
 * Name of your child from \Rover\Fadmin\Options
 */
use \Rover\Fadmin\TestOptions;

if (!Loader::includeModule($mid)
	|| !Loader::includeModule('rover.fadmin'))
	throw new SystemException('module "' . $mid . '" not found!');

Loc::loadMessages(__FILE__);
Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/options.php");

$options = TestOptions::getInstance();
$options->message->addOk('С 1-го сенятбря минимальная поддерживаемая версия php будет 5.6, позаботьтесь о переходе заранее.');

$form = new Form(TestOptions::getInstance());
$form->show();