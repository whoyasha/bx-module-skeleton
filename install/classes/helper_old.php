<?php

/**
 * TO DO вынести в отдельное пространство в папку config в корне модуля
 */ 
 
 /**
  * TO DO Получить dashesToCamelCase из класса helper в bx_utils
  */ 
function dashesToCamelCase($string, $divider = "_") {
	$string = preg_replace('/[\'\/~`\!@#\$%\^&\*\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', "", $string);
	$str = str_replace($divider, '', \ucwords($string, $divider));
	return $str;
}

function initBxModule( $MODULE_ID ) {
	
	$init_class = $MODULE_ID . "_init";
	$class_name = dashesToCamelCase($init_class);
	
	if ( !class_exists($class_name) ) {
		$path = $_SERVER["DOCUMENT_ROOT"] . getLocalPath("modules/" . $MODULE_ID . "/install/init.php");
		if ( file_exists($path) )
			include($path);
	}

	if ( class_exists($class_name) )
		return $class_name::getInstance()->init();
			
	return false;
}