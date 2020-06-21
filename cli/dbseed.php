<?php

namespace PGNChessData\Cli;

use Dotenv\Dotenv;
use PGNChessData\Exception\PgnFileCharacterEncodingException;
use PGNChessData\File\Seed as PgnFileSeed;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();

if (!in_array('--quiet', $argv)) {
    echo 'This will search for valid PGN games in the file.' . PHP_EOL;
    echo 'Large files (for example 50MB) may take a few seconds to be inserted into the database.' . PHP_EOL;
    echo 'Do you want to proceed? (Y/N): ';
    $handle = fopen ('php://stdin','r');
    $line = fgets($handle);
    if (trim($line) != 'Y' && trim($line) != 'y') {
        exit;
    }
    fclose($handle);
}

try {
    $result = (new PgnFileSeed($argv[1]))->db();
} catch (PgnFileCharacterEncodingException $e) {
    echo $e->getMessage() . PHP_EOL;
    exit;
}
