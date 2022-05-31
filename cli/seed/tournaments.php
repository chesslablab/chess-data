<?php

namespace ChessData\Cli\Seed;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\PdoCli;

class Tournaments extends PdoCli
{
    protected $table = 'tournaments';
}

$cli = new Tournaments();
$cli->run();
