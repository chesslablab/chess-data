<?php
use PGNChess\Board;
use PGNChess\PGN;

require_once __DIR__ . '/../vendor/autoload.php';

$game = <<<EOT
    1. d4 d6 2. c4 g6 3. Nc3 Bg7 4. e4 e6 5. Nf3 Ne7 6. Bd3 O-O 7. Qc2 Nd7
    8. O-O c5 9. d5 exd5 10. exd5 Ne5 11. Nxe5 Bxe5 12. f4 Bd4+ 13. Kh1 Nf5
    14. Rf3 Nh4 15. Rg3 Bf2 16. Qxf2 Re8
EOT;

$pairs = array_filter(preg_split('/[0-9]+\./', $game));
$moves = [];

foreach ($pairs as $pair)
{
    $moves[] = array_values(array_filter(array_unique(explode(' ', $pair))));
}

$moves = array_values(array_filter($moves));

$board = new Board;

for ($i=0; $i<count($moves); $i++)
{
    try
    {
        if ($board->play(PGN::objectizeMove(PGN::COLOR_WHITE, $moves[$i][0])))
        {
            echo PGN::COLOR_WHITE . " played {$moves[$i][0]}, OK..." . PHP_EOL;
        }
        else
        {
            echo PGN::COLOR_WHITE . " played {$moves[$i][0]}, illegal move." . PHP_EOL;
            exit;
        }
        if ($board->play(PGN::objectizeMove(PGN::COLOR_BLACK, $moves[$i][1])))
        {
            echo PGN::COLOR_BLACK . " played {$moves[$i][1]}, OK..." . PHP_EOL;
        }
        else
        {
            echo PGN::COLOR_BLACK . " played {$moves[$i][1]}, illegal move." . PHP_EOL;
            exit;
        }
    }
    catch (\InvalidArgumentException $e)
    {
        echo $e->getMessage() . PHP_EOL;
        exit;
    }
}
