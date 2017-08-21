<?php

if (!preg_match('/^game-/', $argv[1])) {
    exit;
}

$pgn = file_get_contents(__DIR__ . "/{$argv[1]}.pgn");

require_once 'print-game.php';
