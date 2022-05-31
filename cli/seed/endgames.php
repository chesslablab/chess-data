<?php

namespace ChessData\Cli\Seed;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\PdoCli;

class Endgames extends PdoCli
{
    protected $table = 'endgames';
}

$cli = new Endgames();
$cli->run();
