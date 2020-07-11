<?php

namespace PGNChessData\Api;

use PGNChessData\Pdo;

$input = file_get_contents('php://input');
$json = json_decode($input);

$result = Pdo::getInstance()
            ->query($json->sql)
            ->fetchAll(\PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($result);
