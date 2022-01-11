<?php

namespace ChessData;

use Dotenv\Dotenv;
use splitbrain\phpcli\CLI;

abstract class PdoCli extends CLI
{
    protected $pdo;

    public function __construct()
    {
        parent::__construct(true);

        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();

        $conf = include(__DIR__.'/../config/database.php');

        $this->pdo = Pdo::getInstance($conf);
    }
}
