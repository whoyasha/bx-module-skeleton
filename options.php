<?
/** @global CUser $USER */
/** @global CMain $APPLICATION */
/** @global string $mid */

use \Bitrix\Main,
	\Bitrix\Main\Loader,
	\Bitrix\Main\Config\Option;
	
CJSCore::Init(["jquery"]);

$MODULE_ID = array_pop(explode("/", __DIR__));

if ( !Loader::IncludeModule($MODULE_ID) )
	return;

$init = initBxModule($MODULE_ID);

// $errors = $init->get("ERRORS");

if ( strlen($errors) > 0 ) {
	CAdminMessage::ShowMessage([
		'MESSAGE' => $errors
	]);
}

$options = $init->getOptions("Get Options here");

echo '<pre>$options in options.php ' . __FUNCTION__ . ' : '; print_r($options); echo'</pre>';

if ( !$options )
	return;
	
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

// $arTabs = $options->Tabs();
// echo '<pre>$arTabs in ' . __FUNCTION__ . ' : '; print_r($arTabs); echo'</pre>';


