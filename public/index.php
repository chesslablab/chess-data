<?php

require realpath(dirname(__FILE__)) .'/../src/bootstrap.php';

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range");

switch (true) {
    case '/api/query' === $_SERVER['REQUEST_URI'] && $_SERVER['REQUEST_METHOD'] === 'POST':
        $input = file_get_contents('php://input');
        $json = json_decode($input);
        $result = Pdo::getInstance()->query($json->sql)->fetchAll(\PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    case $_SERVER['REQUEST_METHOD'] === 'OPTIONS':
        http_response_code(204);
        exit;
    default:
        http_response_code(404);
        exit;
}
