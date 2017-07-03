<?php
use PGNChess\Board;
use PGNChess\PGN;

require_once __DIR__ . '/../vendor/autoload.php';

$game = <<<EOT
    1. e4 c5 2. Nf3 e6 3. Bc4 a6 4. d3 b5 5. Bb3 Nc6 6. O-O Nf6 7. e5 Nd5
    8. c4 bxc4 9. dxc4 Ndb4 10. Bf4 Bb7 11. a3 Nd4 12. axb4 Nxf3+ 13. gxf3 Be7
    14. bxc5 Bxc5 15. Nc3 Qh4 16. Bg3 Qh5 17. Ne4 Bxe4 18. fxe4 Qg6 19. Ba4 O-O
    20. Qxd7 h5 21. Rac1 h4 22. b4 hxg3 23. bxc5 gxh2+ 24. Kxh2 Qxe4 25. Bc2 Qxe5+
    26. Kg2 g6 27. Rh1 Kg7 28. Bxg6 Qg5+ 29. Kf1 Qxg6 30. Rg1 Qxg1+ 31. Kxg1 Rg8
    32. Kf1 Kf6 33. Qd4+ Ke7 34. Qf4 Rgd8 35. c6 Rac8 36. c7 Rd6 37. c5 Rc6
    38. Qh4+ Kf8 39. Qd8+ Rxd8 40. cxd8=Q+ Kg7 41. Qd7 Rxc5 42. Qd4+ e5 43. Qxc5 Kf6
    44. Rc4 Kg7 45. Re4 f6 46. Ke2 Kg6 47. Ke3 Kf7 48. f4 exf4+ 49. Kxf4 Kg7
    50. Qc7+ Kg6 51. Re6
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
