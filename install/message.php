<?php

use Bitrix\Main\Localization\Loc;

global $APPLICATION, $fadminErrors;

if (empty($fadminErrors)) {
    CAdminMessage::ShowNote(Loc::getMessage("MOD_INST_OK"));
} else {
    CAdminMessage::ShowMessage(
        [
            "TYPE"    => "ERROR",
            "MESSAGE" => Loc::getMessage("MOD_INST_ERR"),
            "DETAILS" => implode("<br/>", $fadminErrors),
            "HTML"    => true
        ]);
}

?>
<form action="<?php echo $APPLICATION->GetCurPage() ?>">
    <input type="hidden" name="lang" value="<?= LANG ?>">
    <input type="submit" name="" value="<?= Loc::getMessage("MOD_BACK") ?>">
    <form>