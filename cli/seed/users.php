<?php

namespace ChessData\Cli\Seed;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\PdoUserCli;

class Users extends PdoUserCli
{
    protected $table = 'users';
}

$cli = new Users();
$cli->run();
