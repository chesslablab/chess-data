<?php
use PGNChess\Board;
use PGNChess\PGN;

require_once __DIR__ . '/../vendor/autoload.php';

$game = [
    'e4 e5',
    /* 'f4 exf4',
    'd4 Nf6',
    'Nc3 Bb4',
    'Bxf4 Bxc3+',
    'bxc3 Nxe4',
    'Qe2 d5',
    'c4 O-O',
    'Nf3 Nc3',
    'Qd3 Re8+',
    'Kd2 Ne4+',
    'Kc1 Nf2' */
];

$board = new Board;

foreach ($game as $entry)
{
    $moves = explode(' ', $entry);
    try
    {
        if ($board->play(PGN::objectizeMove(PGN::COLOR_WHITE, $moves[0])))
        {
            echo PGN::COLOR_WHITE . " played {$moves[0]}, OK..." . PHP_EOL;
        }
        if ($board->play(PGN::objectizeMove(PGN::COLOR_BLACK, $moves[1])))
        {
            echo PGN::COLOR_BLACK . " played {$moves[1]}, OK..." . PHP_EOL;
        }
    }
    catch (\InvalidArgumentException $e)
    {
        echo $e->getMessage() . PHP_EOL;
        exit;
    }
    print_r($board->getStatus()); exit;
}
