<?php
if ( !class_exists("\Whoyasha\ModuleInit") )
	include(__DIR__ . "/classes/init.php");
	
if ( !class_exists("\Whoyasha\ModuleParams") )
	include(__DIR__ . "/classes/params.php");

$MODULE_ID = array_slice(explode("/", __DIR__), -3, 1)[0];	

if ( $params_json = \Whoyasha\ModuleParams::get($MODULE_ID) ) {
	$class = 'Class ' . $MODULE_ID . '_init extends Whoyasha\ModuleInit { public function SetSettings() {return \Bitrix\Main\Web\Json::decode(\'' . $params_json . '\', true);} }';
	eval($class);
} else {
	return false;
}