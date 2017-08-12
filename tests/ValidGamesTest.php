<?php
namespace PGNChess\Tests;

use PGNChess\PGN;
use PGNChess\Board;

class ValidGamesTest extends \PHPUnit_Framework_TestCase
{
    protected function play($game)
    {
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

    public function testGame05()
    {
        $board = new Board;
        $game = <<<EOT
            1. e4 e5 2. f4 d6 3. Nf3 exf4 4. d4 g6 5. Bxf4 Bg7 6. c3 Bg4 7. Be2 Bxf3
            8. Bxf3 Ne7 9. O-O O-O 10. Qd2 Nd7 11. Bh6 Nf6 12. Bxg7 Kxg7 13. Qg5 Nfg8
            14. Nd2 f6 15. Qh4 c6 16. Qg3 f5 17. e5 dxe5 18. Qxe5+ Nf6 19. Be2 Re8
            20. Rf3 Nd5 21. Qg3 Qb8 22. Bc4 Qxg3 23. Rxg3 Re3 24. Nf3 Ne4 25. Rh3 g5
            26. Bxd5 cxd5 27. Ne5 Rxh3 28. gxh3 Re8 29. Rf1 f4 30. Rxf4 gxf4 31. h4 h5
            32. h3 Ng3 33. Kg2 Ne2 34. Kf3 Ng1+ 35. Kxf4 Nxh3+ 36. Kg3 Ng1 37. Kf2 Nh3+
            38. Kg3 Ng1 39. Kf2 Rf8+ 40. Kxg1 Rf4 41. Nd7 Rxh4 42. Nc5 b6 43. Na4 Re4
            44. Kf2 Kg6 45. Kf3 Re1 46. b3 Kg5 47. c4 h4 48. cxd5 Rd1 49. Nc3 Rxd4 50. Ne2 Rxd5
            51. Ke4 Rd1 52. Ke3 h3 53. Ng3 h2 54. Ke2 h1=Q 55. Nxh1 Rxh1 56. Kd3 Rc1
            57. Kd2 Rc8 58. Kd3 Kf5 59. Kd4 Ke6 60. Kd3 Kd5 61. Kd2 Kc5 62. Ke3 Kb5
            63. Kf3 Kb4 64. Ke4 Ka3 65. Kf3 Kxa2 66. Ke2 Kxb3 67. Ke3 Kc3 68. Kf3 b5
            69. Kf2 b4 70. Kf3 b3 71. Ke4 b2 72. Ke5 b1=Q 73. Kd5 Qd1+ 74. Ke6 Re8+
            75. Kf5 Qd7+ 76. Kf4 Qf7+ 77. Kg3
EOT;
        $this->play($game);
    }

    public function testGame06()
    {
        $board = new Board;
        $game = <<<EOT
            1. e4 c5 2. Nf3 Nc6 3. Bb5 d6 4. O-O Bd7 5. c3 g6 6. d4 cxd4 7. cxd4 Bg7
            8. Be3 e6 9. Nc3 Nge7 10. d5 Ne5 11. Nxe5 Bxe5 12. Bxd7+ Qxd7 13. f4 Bg7
            14. Bd4 Bxd4+ 15. Qxd4 O-O-O 16. Qxa7 exd5 17. Nxd5 Nxd5 18. exd5 Qb5
            19. Rac1+ Kd7 20. Qf2 Qxd5 21. Qg3 Qxa2 22. Qg4+ f5 23. Qg5 Qxb2 24. Rb1 Qd4+
            25. Kh1 Rb8 26. Rbe1 Rhe8 27. Qh6 Rh8 28. Qg5 Rbe8 29. Rb1 Qe4 30. h3 Qe2
            31. Rxb7+ Kc6 32. Rfb1 Qe3 33. Qf6 Rhf8 34. Qb2 Qxf4 35. Rc1+ Kd5 36. Rb5+ Ke6
            37. Qe2+ Qe4 38. Qa2+ Kf6 39. Rb7 d5 40. Rxh7 d4 41. Qa6+ Re6 42. Qa7 d3
            43. Qg7+ Kg5 44. Qh6+ Kf6 45. Qg7+ Kg5 46. Qh6+ Kf6 47. Qg7+ Kg5 48. Qh6+ Kf6
            49. Qg7+
EOT;
        $this->play($game);
    }

    public function testGame07()
    {
        $board = new Board;
        $game = <<<EOT
            1. e4 e5 2. Nc3 d6 3. b3 Be7 4. Bb2 Nf6 5. Nge2 O-O 6. g3 a6 7. Bg2 b5
            8. d3 Bb7 9. h4 h6 10. h5 Nbd7 11. f3 b4 12. Na4 a5 13. g4 Nh7 14. Qd2 c5
            15. f4 Bh4+ 16. Kd1 exf4 17. Nxf4 Bg5 18. Qf2 Ne5 19. d4 Nxg4 20. Qf3 Ngf6
            21. d5 Qd7 22. Bh3 Qe7 23. Bf5 Bc8 24. Bxf6 Qxf6 25. Nh3 Qxa1+ 26. Ke2 Qd4
            27. Bxh7+ Kxh7 28. Nxg5+ hxg5 29. Qf5+ Kg8 30. Qxg5 Qf6 31. Qg3 Re8
            32. Kd3 Qe5 33. Qh4 Qd4+ 34. Ke2 c4 35. Kf3 cxb3 36. h6 g6 37. Nb6 Ra7
            38. axb3 Rae7 39. Nc4 Qxe4+ 40. Qxe4 Rxe4 41. Nxd6 Re3+ 42. Kf4 Rd8
            43. h7+ Kh8 44. Nxf7+ Kg7 45. h8=Q+ Kxf7 46. Qxd8 Re8 47. Rh7+ Kg8 48. Qh4 Rf8+
            49. Kg5 Rf5+ 50. Kxg6 Rf6+ 51. Qxf6
EOT;
        $this->play($game);
    }

    public function testGame08()
    {
        $board = new Board;
        $game = <<<EOT
            1. d4 d6 2. Nf3 Nf6 3. e3 g6 4. Bd3 Bg7 5. Nbd2 O-O 6. c3 b6 7. O-O Bb7
            8. Qc2 Nbd7 9. e4 c5 10. e5 dxe5 11. dxe5 Nd5 12. c4 Nc7 13. Be4 Bxe4
            14. Nxe4 f6 15. exf6 exf6 16. Nd6 Ne8 17. Rd1 Nxd6 18. Rxd6 Qc7 19. Rd5 f5
            20. Bd2 Nf6 21. Rd3 Ne4 22. Bg5 Nxg5 23. Nxg5 Qe5 24. Rb1 Rfe8 25. Nf3 Qe2
            26. Rd2 Qe7 27. Rbd1 g5 28. Kf1 g4 29. Ng1 Bd4 30. Ne2 Qh4 31. Nxd4 Qxh2
            32. f3 cxd4 33. Qxf5 Qh1+ 34. Kf2 Qh4+ 35. Kg1 g3 36. Qg4+ Qxg4 37. fxg4 Re4
            38. Rxd4 Rxd4 39. Rxd4 Re8 40. Rd1 Re4 41. b3 Rxg4 42. Rd3 Kf7 43. Rf3+ Kg6
            44. Kf1 h5 45. Ke2 h4 46. Re3 Kf5 47. Kf3 Rf4+ 48. Ke2 Rf2+ 49. Kd3 h3
            50. gxh3 g2 51. Rg3 Rf1 52. Rxg2 Rh1 53. Rg3 Kf4 54. Re3 Rd1+ 55. Ke2 Ra1
            56. Rf3+ Kg5 57. a4 Ra2+ 58. Kd3 Ra3 59. Kc3 Ra1 60. Rg3+ Kh4 61. Rg7 Kxh3
            62. Rxa7 Rc1+ 63. Kb4 Rb1 64. Rb7 Rf1 65. Rxb6 Rf8 66. a5 Kg4 67. a6 Kf5
            68. Kb5 Ke5 69. c5 Kd5 70. b4 Rc8 71. Rd6+ Ke5 72. a7 Rg8 73. Rb6 Ra8
            74. Ka6 Kd5 75. Rb8 Rxa7+ 76. Kxa7 Kc4 77. c6 Kd5 78. c7 Kc4 79. c8=Q+ Kd3
            80. Qe8 Kd4 81. Rd8+ Kc4 82. Rb8 Kb3 83. Qc6 Ka3 84. b5 Kb3 85. b6
EOT;
        $this->play($game);
    }

    public function testGame09()
    {
        $board = new Board;
        $game = <<<EOT
            1. e4 e6 2. Nf3 d5 3. exd5 exd5 4. d4 Nf6 5. c4 dxc4 6. Bxc4 Be7 7. O-O O-O
            8. Re1 Bg4 9. Qd3 Re8 10. Bg5 Nc6 11. Nbd2 Bxf3 12. Nxf3 h6 13. Bxf6 Bxf6
            14. Rxe8+ Qxe8 15. Re1 Qd7 16. d5 Nb4 17. Qb3 a5 18. d6 Qxd6 19. Bxf7+ Kh7
            20. a3 Nd3 21. Qc2 Kh8 22. Re6 Qd7 23. Re8+ Rxe8 24. Bxe8 Qxe8 25. Qxd3 Bxb2
            26. h3 Qe7 27. a4 b6 28. Qd5 Bf6 29. g3 Qd8 30. Qxd8+ Bxd8 31. Nd4 Be7
            32. f3 Bd6 33. g4 Kg8 34. Kf1 Kf7 35. Ke2 Kf6 36. Ke3 Ke5 37. Nf5 Kf6
            38. Ke4 Kg6 39. Nxd6 cxd6 40. h4 Kf6 41. Kd5 Ke7 42. f4 g6 43. f5 gxf5
            44. g5 hxg5 45. hxg5 f4 46. Ke4 f3 47. Kxf3 Kf7 48. Ke4 Kg6 49. Kd5 Kxg5
            50. Ke6 Kf4 51. Kxd6 Ke4 52. Kc6 Kd4 53. Kxb6 Kc4 54. Kxa5 Kc5 55. Ka6 Kc6
            56. a5 Kc5 57. Kb7 Kb5 58. a6 Ka5 59. a7 Kb5 60. a8=Q
EOT;
        $this->play($game);
    }

    public function testGame10()
    {
        $board = new Board;
        $game = <<<EOT
            1. e4 d6 2. g3 g6 3. Bg2 Bg7 4. d4 e6 5. Ne2 Ne7 6. O-O b6 7. c3 Bb7
            8. Nd2 c5 9. Nb3 O-O 10. dxc5 bxc5 11. Bg5 Nd7 12. Re1 f6 13. Be3 Ne5
            14. f4 Ng4 15. Nec1 Nxe3 16. Rxe3 c4 17. Nd4 Qd7 18. Kh1 d5 19. e5 fxe5
            20. fxe5 Rf2 21. Nce2 Nf5 22. Nxf5 Rxf5 23. Qd4 Qc7 24. Nf4 Bxe5 25. Nxe6 Bxd4
            26. Nxc7 Bxe3 27. Nxa8 Bxa8 28. Re1 Re5 29. Rf1 Bc6 30. Rf6 Ba4 31. h4 Bc1
            32. Rd6 Re1+ 33. Kh2 Bxb2 34. Bxd5+ Kg7 35. Bxc4 Bxc3 36. Ra6 Be8 37. Rxa7+ Kh6
            38. Bg8 Re2+ 39. Kh3 Bg7 40. a4 Rf2 41. Bd5 Rf1 42. g4 Ra1 43. g5+ Kh5 44. Bf3#
EOT;
        $this->play($game);
    }

    public function testGame11()
    {
        $board = new Board;
        $game = <<<EOT
            1. d4 b6 2. e4 Bb7 3. d5 g6 4. c4 Bg7 5. Qc2 e6 6. Nc3 Ne7 7. Nf3 O-O
            8. Be2 c5 9. Bf4 exd5 10. exd5 d6 11. O-O-O a5 12. a4 Na6 13. Ne4 Nc8
            14. b3 Nb4 15. Qd2 f5 16. Neg5 Qf6 17. Kb1 Qa1#
EOT;
        $this->play($game);
    }

    public function testGame12()
    {
        $board = new Board;
        $game = <<<EOT
            1. d4 c6 2. Bf4 d5 3. Nc3 Nf6 4. Bxb8 Rxb8 5. Qd2 e6 6. f3 Be7 7. g4 O-O
            8. h4 c5 9. O-O-O cxd4 10. Qxd4 b6 11. h5 Bc5 12. Qf4 Bd6 13. Qe3 e5
            14. h6 d4 15. Qg5 g6 16. Ne4 Nxe4 17. Qxd8 Rxd8 18. fxe4 Bxg4 19. Rh4 Bh5
            20. Nf3 Be7 21. Rh3 Rdc8 22. Nxe5 Rc7 23. Rdd3 Rbc8 24. c3 Bg5+ 25. Kb1 dxc3
            26. bxc3 Bf6 27. Nd7 Rxc3 28. Nxf6+ Kf8 29. Rxc3 Rxc3 30. Rxc3
EOT;
        $this->play($game);
    }

    public function testGame13()
    {
        $board = new Board;
        $game = <<<EOT
            1. f4 c5 2. Nf3 Nc6 3. e4 d6 4. c3 Bd7 5. Bb5 Qc7 6. Bxc6 Bxc6 7. d3 O-O-O
            8. Nbd2 Nf6 9. O-O h5 10. Re1 h4 11. h3 Nh5 12. Nf1 e5 13. fxe5 dxe5
            14. Nxh4 Nf4 15. Bxf4 exf4 16. Nf3 Be7 17. Qc2 g5 18. N1h2 Bd7 19. e5 Rh7
            20. d4 Rdh8 21. d5 Qb6 22. c4 g4 23. hxg4 Bxg4 24. Nxg4 Rh1+ 25. Kf2 Bh4+
            26. Nxh4 R1xh4 27. Nf6 Qc7 28. Qf5+ Kb8 29. b3 Ka8 30. Re4 Qa5 31. Rxf4 Qd2+
            32. Kf3 Qc3+ 33. Kf2 Rxf4+ 34. Qxf4 Qxa1 35. Nd7 Qxa2+ 36. Kf3 Qxb3+
            37. Kg4 Rg8+
EOT;
        $this->play($game);
    }

    public function testGame14()
    {
        $board = new Board;
        $game = <<<EOT
            1. c4 e6 2. Nc3 Nf6 3. g3 d5 4. cxd5 exd5 5. Bg2 Be6 6. d4 Bb4 7. Bd2 Bxc3
            8. bxc3 O-O 9. Nf3 Nc6 10. O-O Ne4 11. Bf4 h6 12. Qc2 g5 13. Bc1 Qd7
            14. Ba3 Rfe8 15. Ne5 Nxe5 16. dxe5 f5 17. f3 Nxg3 18. hxg3 f4 19. Qg6+ Qg7
            20. Qxg7+ Kxg7 21. gxf4 gxf4 22. Bc1 Kh7 23. Bxf4 Rg8 24. Kf2 Rg6 25. Rh1 Rag8
            26. Bh3 Bxh3 27. Rxh3 Rg2+ 28. Ke3 c5 29. Rxh6+ Kg7 30. Rah1 Kf8 31. e6 d4+
            32. cxd4 cxd4+ 33. Kxd4 Rxe2 34. Bd6+ Ke8 35. e7 Rd2+ 36. Ke5 Rg5+ 37. Kf6 Rgd5
            38. Rh8+ Kd7 39. Rd8+
EOT;
        $this->play($game);
    }
}
