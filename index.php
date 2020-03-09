<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

echo '<pre>';

//$class = new ReflectionClass(\App\Framework\Container\Container::class);
//$constructor = $class->getConstructor();
//foreach ($constructor->getParameters() as $parameter) {
//    if ($parameter->getType() == 'object') {
//        echo 'gg';
//    }
//}
$stream = fopen('http://localhost:8001/index.php', 'r');
$stats = fstat($stream);
var_dump(stream_get_meta_data($stream));
$read = fread($stream, 50);
fclose($stream);
var_dump($stats);
var_dump($read);
echo '</pre>';