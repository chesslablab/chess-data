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

    public function testGame04()
    {
        $board = new Board;
        $game = <<<EOT
            1. e4 c5 2. Nf3 e6 3. Bc4 a6 4. d3 b5 5. Bb3 Nc6 6. O-O Nf6 7. e5 Nd5
            8. c4 bxc4 9. dxc4 Ndb4 10. Bf4 Bb7 11. a3 Nd4 12. axb4 Nxf3+ 13. gxf3 Be7
            14. bxc5 Bxc5 15. Nc3 Qh4 16. Bg3 Qh5 17. Ne4 Bxe4 18. fxe4 Qg6 19. Ba4 O-O
            20. Qxd7 h5 21. Rac1 h4 22. b4 hxg3 23. bxc5 gxh2+ 24. Kxh2 Qxe4 25. Bc2 Qxe5+
            26. Kg2 g6 27. Rh1 Kg7 28. Bxg6 Qg5+ 29. Kf1 Qxg6 30. Rg1 Qxg1+ 31. Kxg1 Rg8
            32. Kf1 Kf6 33. Qd4+ Ke7 34. Qf4 Rgd8 35. c6 Rac8 36. c7 Rd6 37. c5 Rc6
            38. Qh4+ Kf8 39. Qd8+ Rxd8 40. cxd8=Q+ Kg7 41. Qd7 Rxc5 42. Qd4+ e5 43. Qxc5 Kf6
            44. Rc4 Kg7 45. Re4 f6 46. Ke2 Kg6 47. Ke3 Kf7 48. f4 exf4+ 49. Kxf4 Kg7
EOT;
        $this->play($game);
    }
}
