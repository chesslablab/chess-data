<?php

define('APP_PATH', dirname(dirname(__FILE__)));

require APP_PATH.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(APP_PATH);
$dotenv->load();

switch (true) {
    case '/api/sql/query' === $_SERVER['REQUEST_URI']:
        require APP_PATH . '/src/Api/Sql/Query.php';
        exit;
    default:
        http_response_code(404);
        exit;
}
