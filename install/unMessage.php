<?php

use Bitrix\Main\Localization\Loc;

global $APPLICATION, $fadminErrors;

if (!$fadminErrors)
    echo \CAdminMessage::ShowNote(Loc::getMessage("MOD_UNINST_OK"));
else
    echo \CAdminMessage::ShowMessage(
        array(
            "TYPE"      => "ERROR",
            "MESSAGE"   => Loc::getMessage("MOD_UNINST_ERR"),
            "DETAILS"   => implode("<br/>", $fadminErrors),
            "HTML"      => true
        ));

?>
<form action="<?echo $APPLICATION->GetCurPage()?>">
    <input type="hidden" name="lang" value="<?echo LANG?>">
    <input type="submit" name="" value="<?echo Loc::getMessage("MOD_BACK")?>">
<form>