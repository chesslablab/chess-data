<?php
use PGNChess\Board;
use PGNChess\PGN;

require_once __DIR__ . '/../vendor/autoload.php';

$pairs = array_filter(preg_split('/[0-9]+\./', $game));
$moves = [];

foreach ($pairs as $pair)
{
    $moves[] = array_values(array_filter(explode(' ', $pair)));
}

$moves = array_values(array_filter($moves));

$board = new Board;

for ($i=0; $i<count($moves); $i++)
{
    $whiteMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][0]));
    if (isset($moves[$i][1]))
    {
        $blackMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][1]));
    }
    try
    {
        if ($board->play(PGN::objectizeMove(PGN::COLOR_WHITE, $whiteMove)))
        {
            echo PGN::COLOR_WHITE . " played {$whiteMove}, OK..." . PHP_EOL;
        }
        else
        {
            echo PGN::COLOR_WHITE . " played {$whiteMove}, illegal move." . PHP_EOL;
            exit;
        }
        if (isset($moves[$i][1]))
        {
            if ($board->play(PGN::objectizeMove(PGN::COLOR_BLACK, $blackMove)))
            {
                echo PGN::COLOR_BLACK . " played {$blackMove}, OK..." . PHP_EOL;
            }
            else
            {
                echo PGN::COLOR_BLACK . " played {$blackMove}, illegal move." . PHP_EOL;
                exit;
            }
        }
    }
    catch (\InvalidArgumentException $e)
    {
        echo $e->getMessage() . PHP_EOL;
        exit;
    }
}
