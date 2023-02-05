<?php

$MODULE_ID = array_slice(explode("/", __DIR__), -2, 1)[0];

if ( !class_exists("\Whoyasha\ModuleInit") )
	include(__DIR__ . "/classes/init.php");

if ( $params_json = \Whoyasha\ModuleInstallHelper::getSettings($MODULE_ID) ) {
	
	$class_name = $MODULE_ID . '_init';
	$class_name = \Whoyasha\ModuleInstallHelper::dashesToCamelCase($class_name);
	
	$class = 'Class ' . $class_name . ' extends Whoyasha\ModuleInit { public function SetSettings() {return \Bitrix\Main\Web\Json::decode(\'' . $params_json . '\', true);} }';
	eval($class);

} else {
	return false;
}

