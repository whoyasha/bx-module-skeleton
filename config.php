<?php

Class BxModule {
	
	public static function config($MODULE_ID) {
		
		\Bitrix\Main\Loader::registerAutoLoadClasses($MODULE_ID, ['\CLoader' => "install/classes/CLoader.php"]);
		\Bitrix\Main\Loader::registerAutoLoadClasses($MODULE_ID, ['\BxHelper' => "install/classes/BxHelper.php"]);
		\Bitrix\Main\Loader::registerAutoLoadClasses($MODULE_ID, ['\Whoyasha\ModuleInstallHelper' => "install/classes/helper.php"]);
		\Bitrix\Main\Loader::registerAutoLoadClasses($MODULE_ID, ['\Whoyasha\ModuleOptions' => "install/options.php"]);
		\Bitrix\Main\Loader::registerAutoLoadClasses($MODULE_ID, [$class_name = static::classname($MODULE_ID) => "install/init.php"]);
		
		if ( class_exists($class_name) )
			return new $class_name();
				
		return false;
	}
	
	public static function classname($MODULE_ID) {
		return BxHelper::dashesToCamelCase($MODULE_ID . "_init");
	}
	
}