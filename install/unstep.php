<?if(!check_bitrix_sessid()) return;
$MODULE_ID = array_slice(explode("/", __DIR__), -2, 1)[0];
echo CAdminMessage::ShowNote("Модуль \"" . $MODULE_ID . "\" успешно удален из системы");

$dir = str_replace('install', '', __DIR__) . "log";
removeDir($dir);

function removeDir($dir) {
	if ($objs = glob($dir . '/*')) {
		foreach($objs as $obj) {
			is_dir($obj) ? remove_dir($obj) : unlink($obj);
		}
	}
	rmdir($dir);
}
?>