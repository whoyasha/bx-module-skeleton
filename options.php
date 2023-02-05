<?
/** @global CUser $USER */
/** @global CMain $APPLICATION */
/** @global string $mid */

use \Bitrix\Main,
	\Bitrix\Main\Loader,
	\Bitrix\Main\Config\Option;
	
CJSCore::Init(["jquery"]);

$config = BxModule::config(array_pop(explode("/", __DIR__)));

if ( !Loader::IncludeModule($config->MODULE_ID) )
	return;

echo '<pre>$config : '; print_r($config->MODULE_PATH); echo'</pre>';


if ( strlen($errors) > 0 ) {
	CAdminMessage::ShowMessage([
		'MESSAGE' => $errors
	]);
}

$options = $config->Options();

echo '<pre>$options in options.php ' . __FUNCTION__ . ' : '; print_r($options); echo'</pre>';

if ( !$options )
	return;
	
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

// $arTabs = $options->Tabs();
// echo '<pre>$arTabs in ' . __FUNCTION__ . ' : '; print_r($arTabs); echo'</pre>';


