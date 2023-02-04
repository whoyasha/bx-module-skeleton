<?php
$MODULE_ID = array_pop(explode("/", __DIR__));

include(__DIR__ .  "/install/lib/init.php");

$init_class = $MODULE_ID . "_init";
$init = new $init_class;
// echo '<pre>$init in ' . __FUNCTION__ . ' : '; print_r($init); echo'</pre>';

?>