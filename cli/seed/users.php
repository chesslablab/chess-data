<?php

namespace ChessData\Cli\Seed;

require_once __DIR__ . '/../../vendor/autoload.php';

use splitbrain\phpcli\CLI;

class Users extends CLI
{
    protected $table = 'users';
}

$cli = new Users();
$cli->run();
