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
    Tag::ROUND                      . ' CHAR(8) NULL, ' .
    Tag::WHITE                      . ' CHAR(32) NULL, ' .
    Tag::BLACK                      . ' CHAR(32) NULL, ' .
    Tag::RESULT                     . ' CHAR(8) NULL, ' .
    Tag::FICS_GAMES_DB_GAME_NO      . ' CHAR(16) NULL, ' . // FICS database
    Tag::WHITE_TITLE                . ' CHAR(16) NULL, ' . // player related information
    Tag::BLACK_TITLE                . ' CHAR(16) NULL, ' .
    Tag::WHITE_ELO                  . ' CHAR(8) NULL, ' .
    Tag::BLACK_ELO                  . ' CHAR(8) NULL, ' .
    Tag::WHITE_USCF                 . ' CHAR(8) NULL, ' .
    Tag::BLACK_USCF                 . ' CHAR(8) NULL, ' .
    Tag::WHITE_NA                   . ' CHAR(8) NULL, ' .
    Tag::BLACK_NA                   . ' CHAR(8) NULL, ' .
    Tag::WHITE_TYPE                 . ' CHAR(16) NULL, ' .
    Tag::BLACK_TYPE                 . ' CHAR(16) NULL, ' .
    Tag::EVENT_DATE                 . ' CHAR(16) NULL, ' . // event related information
    Tag::EVENT_SPONSOR              . ' CHAR(32) NULL, ' .
    Tag::SECTION                    . ' CHAR(16) NULL, ' .
    Tag::STAGE                      . ' CHAR(32) NULL, ' .
    Tag::BOARD                      . ' CHAR(8) NULL, ' .
    Tag::OPENING                    . ' CHAR(32) NULL, ' . // opening information
    Tag::VARIATION                  . ' CHAR(32) NULL, ' .
    Tag::SUB_VARIATION              . ' CHAR(32) NULL, ' .
    Tag::ECO                        . ' CHAR(32) NULL, ' .
    Tag::NIC                        . ' CHAR(32) NULL, ' .
    Tag::TIME                       . ' CHAR(16) NULL, ' . // time and date related information
    Tag::TIME_CONTROL               . ' CHAR(16) NULL, ' .
    Tag::UTC_TIME                   . ' CHAR(16) NULL, ' .
    Tag::UTC_DATE                   . ' CHAR(16) NULL, ' .
    Tag::WHITE_CLOCK                . ' CHAR(16) NULL, ' . // clock
    Tag::BLACK_CLOCK                . ' CHAR(16) NULL, ' .
    Tag::SET_UP                     . ' CHAR(8) NULL, ' . // alternative starting positions
    Tag::FEN                        . ' CHAR(64) NULL, ' .
    Tag::TERMINATION                . ' CHAR(32) NULL, ' . // game conclusion
    Tag::ANNOTATOR                  . ' CHAR(32) NULL, ' . // miscellaneous
    Tag::MODE                       . ' CHAR(16) NULL, ' .
    Tag::PLY_COUNT                  . ' CHAR(4) NULL, ' .
    Tag::WHITE_RD                   . ' CHAR(8) NULL, ' .
    Tag::BLACK_RD                   . ' CHAR(8) NULL, ' .
    'movetext  VARCHAR(3072)
) ENGINE = MYISAM';

Pdo::getInstance()->query($sql);
