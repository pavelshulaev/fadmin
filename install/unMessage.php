<?php

use Bitrix\Main\Localization\Loc;

global $APPLICATION, $fadminErrors;

if (!$fadminErrors) {
    CAdminMessage::ShowNote(Loc::getMessage("MOD_UNINST_OK"));
} else {
    CAdminMessage::ShowMessage(
        [
            "TYPE"    => "ERROR",
            "MESSAGE" => Loc::getMessage("MOD_UNINST_ERR"),
            "DETAILS" => implode("<br/>", $fadminErrors),
            "HTML"    => true
        ]);
}

?>
<form action="<?= $APPLICATION->GetCurPage() ?>">
    <input type="hidden" name="lang" value="<? echo LANG ?>">
    <input type="submit" name="" value="<? echo Loc::getMessage("MOD_BACK") ?>">
    <form>