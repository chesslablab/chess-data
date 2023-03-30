<?php

namespace ChessData\Cli\Seed;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\PdoCli;

class Compositions extends PdoCli
{
    protected $table = 'compositions';
}

$cli = new Compositions();
$cli->run();
