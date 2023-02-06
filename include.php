<?php

$MODULE_ID = array_pop(explode("/", __DIR__));
\Bitrix\Main\Loader::registerAutoLoadClasses($MODULE_ID, ['BxModule' => "config.php"]);

$config = BxModule::config($MODULE_ID);

?>