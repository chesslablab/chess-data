<?php

if (!preg_match('/^pgn-/', $argv[1])) {
    exit;
}

require_once __DIR__ . "/pgn/{$argv[1]}.php";

require_once 'print-game.php';
