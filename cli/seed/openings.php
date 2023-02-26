<?php

namespace ChessData\Cli\Seed;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\PdoOpeningCli;

class Openings extends PdoOpeningCli
{
    protected $table = 'openings';
}

$cli = new Openings();
$cli->run();
