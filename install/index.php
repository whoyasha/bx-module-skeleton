<?
$MODULE_ID = array_slice(explode("/", __DIR__), -2, 1)[0];

$path = __DIR__ . "/install.php";

echo '<pre>$path in ind : '; print_r($path); echo'</pre>';

// if ( !class_exists($MODULE_ID) )
	include(__DIR__ . "/install.php");
?>