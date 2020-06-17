<?php

namespace PGNChessData\Cli;

use Dotenv\Dotenv;
use PGNChessData\File\Convert as PgnFileConvert;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();

try {
    $sql = (new PgnFileConvert($argv[1]))->toMySqlScript();
} catch (PgnFileCharacterEncodingException $e) {
    echo $e->getMessage() . PHP_EOL;
    exit;
}

echo $sql;
