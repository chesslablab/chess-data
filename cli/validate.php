<?php

namespace PGNChessData\Cli;

use Dotenv\Dotenv;
use PGNChessData\Exception\PgnFileCharacterEncodingException;
use PGNChessData\File\Validate as PgnFileValidate;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

echo 'This will search for syntax errors in the PGN file.' . PHP_EOL;
echo 'Large files (for example 50MB) may take a few seconds to be parsed. Games not passing the validation will be printed.' . PHP_EOL;
echo 'Do you want to proceed? (Y/N): ';
$handle = fopen ('php://stdin','r');
$line = fgets($handle);
if (trim($line) != 'Y' && trim($line) != 'y') {
    exit;
}
fclose($handle);

try {
    $result = (new PgnFileValidate($argv[1]))->syntax();
} catch (PgnFileCharacterEncodingException $e) {
    echo $e->getMessage() . PHP_EOL;
    exit;
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
