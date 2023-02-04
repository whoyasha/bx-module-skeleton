<?php

$MODULE_ID = array_slice(explode("/", __DIR__), -2, 1)[0];	

if ( !class_exists("Whoyasha\ModuleInstall") )
	include(__DIR__ . "/classes/install.php");
	
if ( !class_exists("Whoyasha\ModuleParams") )
	include(__DIR__ . "/classes/params.php");

if ( $params_json = Whoyasha\ModuleParams::get($MODULE_ID) ) {
	$class = 'Class ' . $MODULE_ID . ' extends \Whoyasha\ModuleInstall { public function SetSettings() {return \Bitrix\Main\Web\Json::decode(\'' . $params_json . '\', true);} }';
	eval($class);
} else {
	return false;
}
?>