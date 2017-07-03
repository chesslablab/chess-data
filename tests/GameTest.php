<?php
namespace PGNChess\Tests;

use PGNChess\PGN;
use PGNChess\Board;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;

class GameTest extends \PHPUnit_Framework_TestCase
{
    public function testGame01()
    {
        $board = new Board;

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

        print_r($moves); exit;

        for ($i=0; $i<count($moves); $i++)
        {
            $i % 2 ? $color = PGN::COLOR_WHITE : $color = PGN::COLOR_BLACK;
            $color === PGN::COLOR_WHITE ? $opponentColor = PGN::COLOR_BLACK : $opponentColor = PGN::COLOR_WHITE;
            $this->assertEquals(true, $board->play(PGN::objectizeMove($color, $moves[$i][0])));
            $this->assertEquals(true, $board->play(PGN::objectizeMove($opponentColor, $moves[$i][1])));
        }
    }


}
