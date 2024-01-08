<?php

namespace ChessData\Cli\Seed;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\PdoCli;

class Players extends PdoCli
{
    protected $table = 'games';
}

$cli = new Players();
$cli->run();
