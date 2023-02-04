<?
$MODULE_ID = array_slice(explode("/", __DIR__), -2, 1)[0];

if ( !class_exists($MODULE_ID) )
	include(__DIR__ . "/install.php");
?>
