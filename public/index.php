<?php

define('APP_PATH', dirname(dirname(__FILE__)));

require APP_PATH.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(APP_PATH);
$dotenv->load();

switch (true) {
    case '/api/query' === $_SERVER['REQUEST_URI'] && $_SERVER['REQUEST_METHOD'] === 'POST':
        require APP_PATH . '/src/Api/Query.php';
        exit;
    default:
        http_response_code(404);
        exit;
}
