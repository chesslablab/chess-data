<?php
use PGNChess\Board;
use PGNChess\PGN;

require_once __DIR__ . '/../vendor/autoload.php';

$game = <<<EOT
    1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 4. Ba4 Nf6 5. Nc3 d6 6. d3 b5 7. Bb3 Be7
    8. Be3 Na5 9. Bd5 Nxd5 10. Nxd5 c5 11. Nxe7 Qxe7 12. O-O Nc6 13. Bg5 f6
    14. Be3 O-O 15. h3 Be6 16. Qd2 Nd4 17. Nh4 Qd7 18. Kh2 g5 19. c3 Nc6
    20. Nf3 g4 21. hxg4 Bxg4 22. Nh4 Kh8 23. Bh6 Rg8 24. f4 Bh5 25. fxe5 fxe5
    26. Nf5 Bg6 27. d4 Bxf5 28. Rxf5 cxd4 29. cxd4 Nxd4 30. Rf6 Rg6
    31. Raf1 Rag8 32. Rf8 Ne6 33. Rxg8+ Kxg8 34. Qf2 Rxh6+ 35. Kg1 Nf4
    36. Qb6 Rg6 37. Qb8+ Kg7 38. Rxf4 exf4
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
    $whiteMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][0]));
    $blackMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][1]));
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
    catch (\InvalidArgumentException $e)
    {
        echo $e->getMessage() . PHP_EOL;
        exit;
    }
}
