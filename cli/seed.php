<?php

namespace PGNChessData\Cli;

use Dotenv\Dotenv;
use PGNChessData\Exception\PgnFileCharacterEncodingException;
use PGNChessData\Seeder\Basic as BasicSeeder;
use PGNChessData\Seeder\Heuristic as HeuristicSeeder;

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
  if (in_array('--heuristics', $argv)) {
    $result = (new HeuristicSeeder($argv[1]))->db();
  } else {
    $result = (new BasicSeeder($argv[1]))->db();
  }
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

if ($result->valid === 0) {
    echo 'Whoops! It seems as if no games are valid in this file.' . PHP_EOL;
} else {
    $invalid = $result->total - $result->valid;
    if ($invalid > 0) {
        echo "{$invalid} games did not pass the validation." . PHP_EOL;
    }
    echo "{$result->valid} games out of a total of {$result->total} are OK." . PHP_EOL;
}
