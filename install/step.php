<?if(!check_bitrix_sessid()) return;
$MODULE_ID = array_slice(explode("/", __DIR__), -2, 1)[0];
echo CAdminMessage::ShowNote("Модуль \"". $MODULE_ID . "\" успешно установлен");
?>