<?php

namespace PGNChessData\Cli;

use Dotenv\Dotenv;
use PGNChess\PGN\Tag;
use PGNChessData\Pdo;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();

if (!in_array('--quiet', $argv)) {
    echo 'This will remove the current PGN Chess database and the data will be lost.' . PHP_EOL;
    echo 'Do you want to proceed? (Y/N): ';
    $handle = fopen ('php://stdin','r');
    $line = fgets($handle);
    if (trim($line) != 'Y' && trim($line) != 'y') {
        exit;
    }
    fclose($handle);
}

$sql = 'CREATE DATABASE IF NOT EXISTS ' . getenv('DB_NAME');

Pdo::getInstance()->query($sql);

$sql = 'DROP TABLE IF EXISTS games';

Pdo::getInstance()->query($sql);

$sql = 'CREATE TABLE games (' .
    Tag::EVENT                      . ' CHAR(64) NULL, ' . // STR (Seven Tag Roster)
    Tag::SITE                       . ' CHAR(64) NULL, ' .
    Tag::DATE                       . ' CHAR(16) NULL, ' .
    Tag::WHITE                      . ' CHAR(32) NULL, ' .
    Tag::BLACK                      . ' CHAR(32) NULL, ' .
    Tag::RESULT                     . ' CHAR(8) NULL, ' .
    Tag::WHITE_ELO                  . ' CHAR(8) NULL, ' .
    Tag::BLACK_ELO                  . ' CHAR(8) NULL, ' .
    Tag::ECO                        . ' CHAR(8) NULL, ' .
    'movetext  VARCHAR(3072), attack JSON, center JSON, material JSON, space JSON  
) ENGINE = MYISAM';

Pdo::getInstance()->query($sql);
