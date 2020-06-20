<?php

namespace PGNChessData\Cli;

use Dotenv\Dotenv;
use PGNChessData\File\Validate as PgnFileValidate;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();

echo 'This will search for syntax errors in the PGN file.' . PHP_EOL;
echo 'Large files (for example 50MB) may take a few seconds to be parsed.' . PHP_EOL;
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

if (!empty($result->errors)) {
    echo '--------------------------------------------------------' . PHP_EOL;
    foreach ($result->errors as $error) {
        if (!empty($error['tags'])) {
            foreach ($error['tags'] as $key => $val) {
                echo "$key: $val" . PHP_EOL;
            }
        }
        if (!empty($error['movetext'])) {
            echo $error['movetext'] . PHP_EOL;
        }
        echo '--------------------------------------------------------' . PHP_EOL;
    }
    echo count($result->errors).' games listed above did not pass the validation.' . PHP_EOL;
}

if ($result->valid === 0) {
    echo 'Whoops! It seems as if no games are valid in this file.' . PHP_EOL;
} elseif (!empty($result->errors)) {
    echo "{$result->valid} games are valid." . PHP_EOL;
} else {
    echo "Good! This is a valid PGN file. {$result->valid} games passed the validation." . PHP_EOL;
}
