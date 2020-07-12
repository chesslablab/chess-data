<?php

require realpath(dirname(__FILE__)) .'/../src/bootstrap.php';

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range");

switch (true) {
    case '/api/query' === $_SERVER['REQUEST_URI'] && $_SERVER['REQUEST_METHOD'] === 'POST':
        require APP_PATH . '/src/Api/Query.php';
        exit;
    case $_SERVER['REQUEST_METHOD'] === 'OPTIONS':
        http_response_code(204);
        exit;
    default:
        http_response_code(404);
        exit;
}
