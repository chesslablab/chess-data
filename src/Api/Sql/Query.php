<?php

namespace PGNChessData\Api\Sql;

use PGNChessData\Pdo;

$sql = "SELECT * FROM games";

$result = Pdo::getInstance()
            ->query($sql)
            ->fetchAll(\PDO::FETCH_ASSOC);

echo json_encode($result);
