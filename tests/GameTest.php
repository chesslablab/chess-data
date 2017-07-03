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
    protected function play($game)
    {
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
            $this->assertEquals(true, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, $whiteMove)));
            if (isset($moves[$i][1])) {
                $blackMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][1]));
                $this->assertEquals(true, $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, $blackMove)));
            }
        }
    }

    public function testGame01()
    {
        $board = new Board;
        $game = <<<EOT
            1. d4 d6 2. c4 g6 3. Nc3 Bg7 4. e4 e6 5. Nf3 Ne7 6. Bd3 O-O 7. Qc2 Nd7
            8. O-O c5 9. d5 exd5 10. exd5 Ne5 11. Nxe5 Bxe5 12. f4 Bd4+ 13. Kh1 Nf5
            14. Rf3 Nh4 15. Rg3 Bf2 16. Qxf2 Re8
EOT;
        $this->play($game);

    }

    public function testGame02()
    {
        $board = new Board;
        $game = <<<EOT
            1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 4. Ba4 Nf6 5. Nc3 d6 6. d3 b5 7. Bb3 Be7
            8. Be3 Na5 9. Bd5 Nxd5 10. Nxd5 c5 11. Nxe7 Qxe7 12. O-O Nc6 13. Bg5 f6
            14. Be3 O-O 15. h3 Be6 16. Qd2 Nd4 17. Nh4 Qd7 18. Kh2 g5 19. c3 Nc6
            20. Nf3 g4 21. hxg4 Bxg4 22. Nh4 Kh8 23. Bh6 Rg8 24. f4 Bh5 25. fxe5 fxe5
            26. Nf5 Bg6 27. d4 Bxf5 28. Rxf5 cxd4 29. cxd4 Nxd4 30. Rf6 Rg6
            31. Raf1 Rag8 32. Rf8 Ne6 33. Rxg8+ Kxg8 34. Qf2 Rxh6+ 35. Kg1 Nf4
            36. Qb6 Rg6 37. Qb8+ Kg7 38. Rxf4 exf4
EOT;
        $this->play($game);
    }

    public function testGame03()
    {
        $board = new Board;
        $game = <<<EOT
            1. d4 e6 2. c3 Nf6 3. Nf3 Be7 4. Bg5 h6 5. Bxf6 Bxf6 6. e3 O-O 7. Nbd2 d5
            8. h3 Nd7 9. Be2 b6 10. O-O Bb7 11. b3 c5 12. a4 cxd4 13. cxd4 Re8
            14. Bb5 Rc8 15. Qe2 Bc6 16. Rfc1 Bxb5 17. Qxb5 Nf8 18. Qa6 Qd7 19. Ne1 Ng6
            20. Nd3 Nh4 21. Nf1 Bg5 22. Nh2 f6 23. Ng4 f5 24. Nge5 Rxc1+ 25. Rxc1 Qe7
            26. Qb5 Bf6 27. Qb4 Bxe5 28. Qxe7 Rxe7 29. Nxe5 g5 30. b4 Kh7 31. a5 bxa5
            32. bxa5 Ng6 33. Nxg6 Kxg6 34. Rc6 h5 35. a6 g4 36. hxg4 hxg4 37. g3 Kg5
            38. Kf1 Rh7 39. Kg2 Rh6 40. Rc7 e5 41. Rxa7 exd4 42. exd4 f4 43. gxf4+ Kxf4
            44. Rb7 Rg6 45. a7 Ra6 46. Rf7+ Ke4 47. Kg3 Kxd4 48. Kxg4 Ra2 49. f4 Kc5
            50. Kf5 Kb6 51. Rd7 Rxa7 52. Rxd5 Rf7+ 53. Ke5 Kc7 54. f5 Rh7 55. Ke6 Rh5
            56. f6 Rxd5 57. Kxd5 Kd8 58. Ke6 Ke8 59. Kd6 Kf7 60. Ke5 Kg6 61. Ke6 Kh7
            62. Ke7 Kg8 63. f7+ Kg7 64. f8=Q+
EOT;
        $this->play($game);
    }

    public function testGame04()
    {
        $board = new Board;
        $game = <<<EOT
            1. e4 c5 2. Nf3 Nc6 3. Bb5 e6 4. O-O a6 5. Ba4 Qc7 6. c3 b5 7. Bb3 Bb7
            8. d4 c4 9. Bc2 Be7 10. d5 exd5 11. exd5 Ne5 12. Nxe5 Qxe5 13. Re1 Qxd5
            14. Qxd5 Bxd5 15. Bg5 f6 16. Bh4 Kf7 17. Na3 Nh6 18. Be4 Bxe4 19. Rxe4 Bxa3
            20. bxa3 Rhe8 21. Rd4 Re7 22. Rad1 Nf5 23. Rxd7 Nxh4 24. R1d4 Nf5 25. R4d5 g6
            26. g4 Nh4 27. f4 Re8 28. Kf2 g5 29. Kg3 Ng6 30. fxg5 Ne5 31. Rxe7+ Rxe7
            32. gxf6 Kxf6 33. g5+ Ke6 34. Rd1 Nd3 35. Rd2 Kf7 36. h4 Kg6 37. Kg4 Re4+
            38. Kf3 Rxh4 39. Re2 Rf4+ 40. Kg3 Rf5 41. Re6+ Kxg5
EOT;
        $this->play($game);
    }
}
