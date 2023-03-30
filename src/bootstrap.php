<?php

define('APP_PATH', dirname(dirname(__FILE__)));

require APP_PATH.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(APP_PATH);
$dotenv->load();
