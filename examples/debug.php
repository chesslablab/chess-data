<?php
use PGNChess\PGN\Convert;
use PGNChess\PGN\Move;
use PGNChess\PGN\Symbol;

const SAMPLE_GAME = 'game-04.pgn';

require_once __DIR__ . '/../vendor/autoload.php';

$pgn = file_get_contents(__DIR__ . '/' . SAMPLE_GAME);

require_once 'print-game.php';
