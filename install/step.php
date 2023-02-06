<?if(!check_bitrix_sessid()) return;
$MODULE_ID = array_slice(explode("/", __DIR__), -2, 1)[0];
echo CAdminMessage::ShowNote("Модуль \"". $MODULE_ID . "\" успешно установлен");

$module_path = str_replace('/install', '', __DIR__);
$log_dir = $module_path . '/log';

if ( !is_dir($log_dir) ) {
	mkdir($log_dir);
}
?>