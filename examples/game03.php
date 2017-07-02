<?php
use PGNChess\Board;
use PGNChess\PGN;

require_once __DIR__ . '/../vendor/autoload.php';

$game = [
    'e4 c5',
    'f4 Nc6',
    'Nf3 e5',
    'fxe5 Qe7',
    'Bb5 Nxe5',
    'O-O Nxf3+',
    'Qxf3 Nf6',
    'd3 a6',
    'Ba4 b5',
    'Bb3 Bb7',
    'Bg5 h6',
    'Bh4 O-O-O',
    'Bxf6 gxf6',
    'Qxf6 Qxf6',
    'Rxf6 Bg7',
    'Rxf7 Bxb2',
    'Rf5 Bxa1',
    'Rxc5+ Bc6',
    'c3 Kb7',
    'Na3 d6',
    'Rf5 Bxc3',
    'Nc2 Rhf8',
    'Rh5 Bd2',
    'Nd4 Be3+',
    'Kh1 Rf1' // white checkmated
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
