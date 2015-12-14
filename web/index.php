<?php
error_reporting(E_ALL);

include_once realpath(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'bootstup.php';
//$startMemory = 0;
//$startMemory = memory_get_usage();
$APP->run();
//echo ((memory_get_usage() - $startMemory)/1000000) . ' Mb' . PHP_EOL;
//echo microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"].' s'.PHP_EOL;



