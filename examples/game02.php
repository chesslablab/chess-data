<?php
use PGNChess\Board;
use PGNChess\PGN;

require_once __DIR__ . '/../vendor/autoload.php';

$game = [
    'e4 c5',
    'Nf3 Nc6',
    'd4 cxd4',
    'Nxd4 Nf6',
    'Nc3 e5',
    'Ndb5 d6',
    'Bg5 a6',
    'Na3 b5',
    'Nd5 Be7',
    'Bxf6 Bxf6',
    'c3 O-O',
    'h4 Rb8',
    'Nc2 Be7',
    'Nce3 Be6',
    'Qf3 Qd7',
    'Rd1 Bd8'
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
        else
        {
            echo PGN::COLOR_WHITE . " played {$moves[0]}, illegal move." . PHP_EOL;
            exit;
        }
        if ($board->play(PGN::objectizeMove(PGN::COLOR_BLACK, $moves[1])))
        {
            echo PGN::COLOR_BLACK . " played {$moves[1]}, OK..." . PHP_EOL;
        }
        else
        {
            echo PGN::COLOR_BLACK . " played {$moves[1]}, illegal move." . PHP_EOL;
            exit;
        }
    }
    catch (\InvalidArgumentException $e)
    {
        echo $e->getMessage() . PHP_EOL;
        exit;
    }
}
