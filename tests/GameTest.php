<?php
namespace PGNChess\Tests;

use PGNChess\Game;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;

class GameTest extends \PHPUnit_Framework_TestCase
{
    protected function play($pgn)
    {
        $pairs = array_filter(preg_split('/[0-9]+\./', $pgn));

        $moves = [];

        foreach ($pairs as $pair) {
            $moves[] = array_values(array_filter(explode(' ', $pair)));
        }

        $moves = array_values(array_filter($moves));

        $game = new Game;

        for ($i=0; $i<count($moves); $i++) {

            $whiteMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][0]));

            $this->assertEquals(true, $game->play(Convert::toObject(Symbol::WHITE, $whiteMove)));

            if (isset($moves[$i][1])) {
                $blackMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][1]));
                $this->assertEquals(true, $game->play(Convert::toObject(Symbol::BLACK, $blackMove)));
            }
        }
    }

    public function testGame01()
    {
        $pgn = <<<EOT
            1. d4 d6 2. c4 g6 3. Nc3 Bg7 4. e4 e6 5. Nf3 Ne7 6. Bd3 O-O 7. Qc2 Nd7
            8. O-O c5 9. d5 exd5 10. exd5 Ne5 11. Nxe5 Bxe5 12. f4 Bd4+ 13. Kh1 Nf5
            14. Rf3 Nh4 15. Rg3 Bf2 16. Qxf2 Re8
EOT;
        $this->play($pgn);
    }

    public function testGame02()
    {
        $pgn = <<<EOT
            1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 4. Ba4 Nf6 5. Nc3 d6 6. d3 b5 7. Bb3 Be7
            8. Be3 Na5 9. Bd5 Nxd5 10. Nxd5 c5 11. Nxe7 Qxe7 12. O-O Nc6 13. Bg5 f6
            14. Be3 O-O 15. h3 Be6 16. Qd2 Nd4 17. Nh4 Qd7 18. Kh2 g5 19. c3 Nc6
            20. Nf3 g4 21. hxg4 Bxg4 22. Nh4 Kh8 23. Bh6 Rg8 24. f4 Bh5 25. fxe5 fxe5
            26. Nf5 Bg6 27. d4 Bxf5 28. Rxf5 cxd4 29. cxd4 Nxd4 30. Rf6 Rg6
            31. Raf1 Rag8 32. Rf8 Ne6 33. Rxg8+ Kxg8 34. Qf2 Rxh6+ 35. Kg1 Nf4
            36. Qb6 Rg6 37. Qb8+ Kg7 38. Rxf4 exf4
EOT;
        $this->play($pgn);
    }

    public function testGame03()
    {
        $pgn = <<<EOT
            1. e4 c5 2. Nf3 Nc6 3. Bb5 e6 4. O-O a6 5. Ba4 Qc7 6. c3 b5 7. Bb3 Bb7
            8. d4 c4 9. Bc2 Be7 10. d5 exd5 11. exd5 Ne5 12. Nxe5 Qxe5 13. Re1 Qxd5
            14. Qxd5 Bxd5 15. Bg5 f6 16. Bh4 Kf7 17. Na3 Nh6 18. Be4 Bxe4 19. Rxe4 Bxa3
            20. bxa3 Rhe8 21. Rd4 Re7 22. Rad1 Nf5 23. Rxd7 Nxh4 24. R1d4 Nf5 25. R4d5 g6
            26. g4 Nh4 27. f4 Re8 28. Kf2 g5 29. Kg3 Ng6 30. fxg5 Ne5 31. Rxe7+ Rxe7
            32. gxf6 Kxf6 33. g5+ Ke6 34. Rd1 Nd3 35. Rd2 Kf7 36. h4 Kg6 37. Kg4 Re4+
            38. Kf3 Rxh4 39. Re2 Rf4+ 40. Kg3 Rf5 41. Re6+ Kxg5
EOT;
        $this->play($pgn);
    }

    public function testGame04()
    {
        $pgn = <<<EOT
            1. e4 c5 2. Nf3 e6 3. Bc4 a6 4. d3 b5 5. Bb3 Nc6 6. O-O Nf6 7. e5 Nd5
            8. c4 bxc4 9. dxc4 Ndb4 10. Bf4 Bb7 11. a3 Nd4 12. axb4 Nxf3+ 13. gxf3 Be7
            14. bxc5 Bxc5 15. Nc3 Qh4 16. Bg3 Qh5 17. Ne4 Bxe4 18. fxe4 Qg6 19. Ba4 O-O
            20. Qxd7 h5 21. Rac1 h4 22. b4 hxg3 23. bxc5 gxh2+ 24. Kxh2 Qxe4 25. Bc2 Qxe5+
            26. Kg2 g6 27. Rh1 Kg7 28. Bxg6 Qg5+ 29. Kf1 Qxg6 30. Rg1 Qxg1+ 31. Kxg1 Rg8
            32. Kf1 Kf6 33. Qd4+ Ke7 34. Qf4 Rgd8 35. c6 Rac8 36. c7 Rd6 37. c5 Rc6
            38. Qh4+ Kf8 39. Qd8+ Rxd8 40. cxd8=Q+ Kg7 41. Qd7 Rxc5 42. Qd4+ e5 43. Qxc5 Kf6
            44. Rc4 Kg7 45. Re4 f6 46. Ke2 Kg6 47. Ke3 Kf7 48. f4 exf4+ 49. Kxf4 Kg7
EOT;
        $this->play($pgn);
    }

    public function testGame05()
    {
        $pgn = <<<EOT
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
        $this->play($pgn);
    }

    public function testGame06()
    {
        $pgn = <<<EOT
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
        $this->play($pgn);
    }

    public function testGame07()
    {
        $pgn = <<<EOT
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
        $this->play($pgn);
    }

    public function testGame08()
    {
        $pgn = <<<EOT
            1.e4 c5 2.Nf3 d6 3.c3 Nf6 4.Bd3 Nc6 5.Bc2 g6 6.h3 Bg7 7.O-O O-O 8.d4 cxd4
            9.cxd4 Nb4 10.Bb3 d5 11.e5 Ne8 12.Nc3 Nc7 13.Bg5 Be6 14.Qd2 Nc6 15.Rfe1 Qd7
            16.Ba4 Rfe8 17.Rac1 Rac8 18.b3 Na8 19.Bb5 a6 20.Bf1 b5 21.a4 b4 22.Na2 Qb7
            23.Rc5 f6 24.Bf4 Nxe5 25.Nxe5 Rxc5 26.dxc5 fxe5 27.Bxe5 Bxe5 28.Rxe5 Nc7
            29.Qxb4 Rb8 30.Qa5 d4 31.Nc1 Rf8 32.c6 Qa7 33.b4 Rf5 34.Rxf5 Bxf5 35.b5 Qc5
            36.Qxc7 Qxc1 37.Qd8+
EOT;
        $this->play($pgn);
    }

    public function testGame09()
    {
        $pgn = <<<EOT
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
        $this->play($pgn);
    }

    public function testGame10()
    {
        $pgn = <<<EOT
            1. e4 d6 2. g3 g6 3. Bg2 Bg7 4. d4 e6 5. Ne2 Ne7 6. O-O b6 7. c3 Bb7
            8. Nd2 c5 9. Nb3 O-O 10. dxc5 bxc5 11. Bg5 Nd7 12. Re1 f6 13. Be3 Ne5
            14. f4 Ng4 15. Nec1 Nxe3 16. Rxe3 c4 17. Nd4 Qd7 18. Kh1 d5 19. e5 fxe5
            20. fxe5 Rf2 21. Nce2 Nf5 22. Nxf5 Rxf5 23. Qd4 Qc7 24. Nf4 Bxe5 25. Nxe6 Bxd4
            26. Nxc7 Bxe3 27. Nxa8 Bxa8 28. Re1 Re5 29. Rf1 Bc6 30. Rf6 Ba4 31. h4 Bc1
            32. Rd6 Re1+ 33. Kh2 Bxb2 34. Bxd5+ Kg7 35. Bxc4 Bxc3 36. Ra6 Be8 37. Rxa7+ Kh6
            38. Bg8 Re2+ 39. Kh3 Bg7 40. a4 Rf2 41. Bd5 Rf1 42. g4 Ra1 43. g5+ Kh5 44. Bf3#
EOT;
        $this->play($pgn);
    }

    public function testGame11()
    {
        $pgn = <<<EOT
            1. d4 b6 2. e4 Bb7 3. d5 g6 4. c4 Bg7 5. Qc2 e6 6. Nc3 Ne7 7. Nf3 O-O
            8. Be2 c5 9. Bf4 exd5 10. exd5 d6 11. O-O-O a5 12. a4 Na6 13. Ne4 Nc8
            14. b3 Nb4 15. Qd2 f5 16. Neg5 Qf6 17. Kb1 Qa1#
EOT;
        $this->play($pgn);
    }

    public function testGame12()
    {
        $pgn = <<<EOT
            1. d4 c6 2. Bf4 d5 3. Nc3 Nf6 4. Bxb8 Rxb8 5. Qd2 e6 6. f3 Be7 7. g4 O-O
            8. h4 c5 9. O-O-O cxd4 10. Qxd4 b6 11. h5 Bc5 12. Qf4 Bd6 13. Qe3 e5
            14. h6 d4 15. Qg5 g6 16. Ne4 Nxe4 17. Qxd8 Rxd8 18. fxe4 Bxg4 19. Rh4 Bh5
            20. Nf3 Be7 21. Rh3 Rdc8 22. Nxe5 Rc7 23. Rdd3 Rbc8 24. c3 Bg5+ 25. Kb1 dxc3
            26. bxc3 Bf6 27. Nd7 Rxc3 28. Nxf6+ Kf8 29. Rxc3 Rxc3 30. Rxc3
EOT;
        $this->play($pgn);
    }

    public function testGame13()
    {
        $pgn = <<<EOT
            1. f4 c5 2. Nf3 Nc6 3. e4 d6 4. c3 Bd7 5. Bb5 Qc7 6. Bxc6 Bxc6 7. d3 O-O-O
            8. Nbd2 Nf6 9. O-O h5 10. Re1 h4 11. h3 Nh5 12. Nf1 e5 13. fxe5 dxe5
            14. Nxh4 Nf4 15. Bxf4 exf4 16. Nf3 Be7 17. Qc2 g5 18. N1h2 Bd7 19. e5 Rh7
            20. d4 Rdh8 21. d5 Qb6 22. c4 g4 23. hxg4 Bxg4 24. Nxg4 Rh1+ 25. Kf2 Bh4+
            26. Nxh4 R1xh4 27. Nf6 Qc7 28. Qf5+ Kb8 29. b3 Ka8 30. Re4 Qa5 31. Rxf4 Qd2+
            32. Kf3 Qc3+ 33. Kf2 Rxf4+ 34. Qxf4 Qxa1 35. Nd7 Qxa2+ 36. Kf3 Qxb3+
            37. Kg4 Rg8+
EOT;
        $this->play($pgn);
    }

    public function testGame14()
    {
        $pgn = <<<EOT
            1. c4 e6 2. Nc3 Nf6 3. g3 d5 4. cxd5 exd5 5. Bg2 Be6 6. d4 Bb4 7. Bd2 Bxc3
            8. bxc3 O-O 9. Nf3 Nc6 10. O-O Ne4 11. Bf4 h6 12. Qc2 g5 13. Bc1 Qd7
            14. Ba3 Rfe8 15. Ne5 Nxe5 16. dxe5 f5 17. f3 Nxg3 18. hxg3 f4 19. Qg6+ Qg7
            20. Qxg7+ Kxg7 21. gxf4 gxf4 22. Bc1 Kh7 23. Bxf4 Rg8 24. Kf2 Rg6 25. Rh1 Rag8
            26. Bh3 Bxh3 27. Rxh3 Rg2+ 28. Ke3 c5 29. Rxh6+ Kg7 30. Rah1 Kf8 31. e6 d4+
            32. cxd4 cxd4+ 33. Kxd4 Rxe2 34. Bd6+ Ke8 35. e7 Rd2+ 36. Ke5 Rg5+ 37. Kf6 Rgd5
            38. Rh8+ Kd7 39. Rd8+
EOT;
        $this->play($pgn);
    }

    public function testGame15()
    {
        $pgn = <<<EOT
            1. c4 Nf6 2. Nc3 e6 3. Nf3 Bb4 4. Qc2 d6 5. e3 O-O 6. Be2 e5 7. a3 Bxc3
            8. Qxc3 c5 9. d3 a5 10. b3 Nc6 11. Bb2 Bg4 12. O-O Re8 13. Rfe1 Rb8 14. Qc2 h6
            15. Rad1 Qc8 16. h3 Bf5 17. Bf1 b5 18. Nd2 Bg6 19. Ba1 Qe6 20. Ne4 Ne7 21. Nxf6+ Qxf6
            22. Qd2 b4 23. axb4 axb4 24. d4 Nc6 25. dxc5 dxc5 26. Qb2 Ra8 27. f4 Be4
            28. Bd3 Bxd3 29. Rxd3 Qg6 30. Red1 f6 31. Rd6 Qe4 32. Qf2 exf4 33. exf4 Na5
            34. Bxf6 gxf6 35. Rxf6 Ra7 36. Qxc5 Qe3+ 37. Qxe3 Rxe3 38. f5 Rxb3 39. Rxh6 Nxc4
            40. f6 Rba3 41. Rd4 b3 42. Rxc4 b2 43. Rc8+ Kf7 44. Rb8 Rxh3 45. Rxh3 Ra1+
            46. Kf2 b1=Q 47. Rxb1 Rxb1 48. Rf3 Rb5 49. g4 Rb8 50. Kg3 Kg6 51. f7 Rf8 52. Rf5 Rxf7
            53. Rxf7 Kxf7 54. Kf4 Kf6 55. Ke4
EOT;

        $this->play($pgn);
    }

    public function testGame16()
    {
        $pgn = <<<EOT
            1. f4 d5 2. Nf3 f5 3. e3 Nf6 4. b3 e6 5. Bb2 Bd6 6. Bd3 O-O 7. O-O Nbd7 8. c4 c6
            9. Nc3 Nc5 10. Bc2 Qe7 11. d4 Nce4 12. Bxe4 Nxe4 13. c5 Bc7 14. b4 Bd7 15. a4 Be8
            16. Ne5 g5 17. b5 Bxe5 18. fxe5 Nxc3 19. Bxc3 g4 20. Rb1 Qg5 21. Bd2 Qg6 22. Be1 Qg5
            23. Rb3 Rc8 24. Bg3 Rc7 25. Bf4 Qg6 26. bxc6 Bxc6 27. Rb2 h5 28. a5 a6 29. Qe1 Bb5
            30. Rff2 h4 31. Rb3 Rg7 32. Rfb2 Bc6 33. Qxh4 Kf7 34. Rxb7+ Bxb7 35. Rxb7+ Kg8
            36. Rb6 Ra8 37. Qe1 Qe8 38. Qb4 Rh7 39. Bg5 Rg7 40. Bf6 Rf7 41. c6 Qf8 42. Qc3 Qh6
            43. c7 f4 44. c8=Q+ Rf8 45. Qxe6+ Kh7 46. Rb7+ Kg6 47. Bh4+ Kh5 48. Qxh6+ Kxh6
            49. exf4 Rxf4 50. Qc6+ Kh5 51. Rh7#
EOT;

        $this->play($pgn);
    }

    public function testGame17()
    {
        $pgn = <<<EOT
            1.e4 g6 2.d4 Bg7 3.Nf3 c6 4.Bd3 d5 5.c3 dxe4 6.Bxe4 Nf6 7.Bc2 O-O 8.O-O Nbd7
            9.Nbd2 b6 10.Re1 Bb7 11.Nf1 c5 12.h3 Rc8 13.dxc5 Rxc5 14.Bf4 e6 15.Ne3 Re8
            16.Qe2 Qa8 17.Nd2 Nh5 18.Bh2 Be5 19.f3 Bxh2+ 20.Kxh2 Qb8+ 21.Kg1 Ndf6
            22.Qf2 Rd8 23.Rad1 Rcc8 24.Nec4 Rd5 25.Ne3 Rd7 26.Ng4 Rc5 27.Qh4 Kg7 28.Be4 Qd8
            29.Bxb7 Rxd2 30.Rxd2 Qxd2 31.Qf2 Qf4 32.Rd1 Rc7 33.Ba6 Qa4 34.Bd3 Qxd1+
EOT;

        $this->play($pgn);
    }

    public function testGame18()
    {
        $pgn = <<<EOT
            1.e4 c5 2.Nc3 Nc6 3.Bb5 e6 4.Bxc6 bxc6 5.d3 d5 6.f4 Nf6 7.Nf3 Be7 8.O-O O-O
            9.Qe2 a5 10.e5 Nd7 11.Kh1 f6 12.exf6 Rxf6 13.Bd2 Bd6 14.Ne5 Qc7 15.Ng4 Rf7
            16.Rae1 Nb6 17.Ne5 Rf8 18.Rf3 Nd7 19.Na4 Nxe5 20.fxe5 Be7 21.Rxf8+ Bxf8 22.c4 Bd7
            23.Qg4 Re8 24.h3 Bc8 25.Qe2 Be7 26.Rf1 Bd7 27.Qh5 g6 28.Qe2 Bf8 29.b3 Bc8 30.Bg5 Bd7
            31.Qe1 Ra8 32.Qf2 Bg7 33.Qf7+ Kh8 34.Bf6
EOT;

        $this->play($pgn);
    }

    public function testGame19()
    {
        $pgn = <<<EOT
            1.e4 g6 2.d4 Bg7 3.Nc3 d5 4.exd5 Nf6 5.Bc4 Nbd7 6.Bg5 O-O 7.Qf3 Nb6 8.Bb3 a5
            9.a4 Bg4 10.Qf4 Bf5 11.Bxf6 exf6 12.Nge2 Bd7 13.O-O f5 14.Qd2 Re8 15.Rad1 Qe7
            16.Qf4 Rac8 17.Nc1 Qf6 18.Qd2 Qd6 19.Qd3 Qf6 20.Qd2 Re7 21.Nb5 Bxb5 22.axb5 a4
            23.Ba2 Re4 24.c3 Rce8 25.Nd3 Qd8 26.Nc5 Re2 27.Qc1 a3 28.Rd2 Bh6 29.f4 Bxf4
            30.Rxf4 Re1+ 31.Rf1 Rxc1 32.Rxc1 Qg5 33.Rcd1 Re1+ 34.Kf2 Qe3#
EOT;

        $this->play($pgn);
    }

    public function testGame20()
    {
        $pgn = <<<EOT
            1.e4 c6 2.Ne2 d5 3.e5 Bf5 4.Ng3 Bg6 5.h4 h6 6.h5 Bh7 7.d4 e6 8.Bd3 Bxd3
            9.Qxd3 Nd7 10.Be3 c5 11.c3 Ne7 12.Nd2 Nc6 13.O-O Rc8 14.a3 cxd4 15.cxd4 a5
            16.f4 Be7 17.Nf3 Qb6 18.f5 Qa6 19.Qd2 Nb6 20.Bf2 Kd7 21.Qf4 a4 22.Qg4 Rhg8
            23.Rfe1 Na5 24.Ne2 Nb3 25.Rab1 Nc4 26.Nf4 Qc6 27.Re2 Rce8 28.Ne1 Ncd2
            29.fxe6+ fxe6 30.Rd1 Bg5 31.Rexd2 Nxd2 32.Rxd2 Qc1 33.Qe2 Qc4 34.Ned3 Rc8
            35.Qg4 Rge8 36.Rd1 b6 37.Rc1 Qb5 38.Rxc8 Kxc8 39.Be1 Qd7 40.Bb4 Kb7
            41.Kh2 Qf7 42.g3 Qc7 43.Qd1 Qc6 44.Kh3 Qb5 45.Kg2 Qc6 46.Ne2 Qc4 47.Ndf4 Qc6
            48.Nc3 Bxf4 49.gxf4 b5 50.Qf1 Rc8 51.Qxb5+ Qxb5 52.Nxb5 Rc2+ 53.Kf3 Rxb2
            54.Nc3 Rd2 55.Nxa4 Rxd4 56.Nc5+ Kc6 57.Nxe6 Rd3+ 58.Kf2 d4 59.Nxg7
EOT;

        $this->play($pgn);
    }

    public function testGame21()
    {
        $pgn = <<<EOT
            1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.Qc2 O-O 5.Nf3 d5 6.cxd5 exd5 7.Bf4 c5
            8.e3 Nc6 9.dxc5 Bxc5 10.Rd1 Qa5 11.Be2 Nb4 12.Qa4 Qb6 13.O-O Be6
            14.a3 Nc6 15.b4 Be7 16.Qc2 Rac8 17.Qb2 a6 18.Ne5 Rfd8 19.Na4 Qa7
            20.Rc1 Nd7 21.Nxd7 Bxd7 22.Nc5 Bf5 23.Rfd1 a5 24.Bd3 Bxd3 25.Nxd3 axb4
            26.axb4 Qb6 27.Be5 Bf8 28.Bc3 Qb5 29.h3 h6 30.Bd4 b6 31.Bc3 Rd7 32.Nf4 Rdd8
            33.Nd3 Ra8 34.Nf4 Ra7 35.Rb1 Rad7 36.Ne2 Rc7 37.Bd4 Rcc8 38.Nc3 Qxb4
            39.Bxb6 Rd7 40.Nxd5 Qxb2 41.Rxb2 Ne5 42.Rbb1 Nc4 43.e4 Rb8 44.Be3 Rxb1
            45.Rxb1 Nxe3 46.Nxe3 Rd4 47.f3 Bc5 48.Kh1 g6 49.Rc1 Bd6 50.Nf1 Rd3 51.Kg1 h5
            52.Kf2 h4 53.Ke2 Ra3 54.Rc8+ Kg7 55.Rc2 Bf4 56.Nd2 Bxd2 57.Rxd2 g5
            58.Kf2 Kg6 59.Rb2 Ra5 60.Re2 Ra3 61.Rd2 Rb3 62.g3 hxg3+ 63.Kxg3 Rb1
            64.Rd6+ Kg7 65.Rd2 Rg1+ 66.Rg2 Rh1 67.Rh2 Rg1+ 68.Kf2 Ra1 69.h4 Ra2+
            70.Kg3 gxh4+ 71.Rxh4 Kf6 72.Kf4 Ra3 73.Rh6+ Ke7 74.Rb6 Rc3 75.e5 Ra3
            76.Ke4 Ra4+ 77.Kf5 Ra7 78.f4 Rc7 79.Kg5 Rc1 80.Ra6 Rb1 81.Ra7+ Ke8 82.Kf6 Rb6+
            83.Kg7 Rg6+ 84.Kh7 Rg4 85.f5 f6 86.exf6 Kf8 87.Kh6 Rg1 88.Kh5 Rg2 89.Ra4 Kf7
            90.Rg4 Rxg4 91.Kxg4 Kxf6 92.Kf4 Kf7 93.Ke5 Ke7 94.f6+ Kf7 95.Kf5 Kf8 96.Kg6 Kg8
            97.f7+ Kf8 98.Kf6
EOT;

        $this->play($pgn);
    }

    public function testGame22()
    {
        $pgn = <<<EOT
            1. Nf3 d5 2. d4 c6 3. c4 e6 4. Nbd2 Nf6 5. e3 Nbd7 6. Bd3 Bd6 7. e4 dxe4
            8. Nxe4 Nxe4 9. Bxe4 O-O 10. O-O h6 11. Bc2 e5 12. Re1 exd4 13. Qxd4 Bc5
            14. Qc3 a5 15. a3 Nf6 16. Be3 Bxe3 17. Rxe3 Bg4 18. Ne5 Re8 19. Rae1 Be6
            20. f4 Qc8 21. h3 b5 22. f5 Bxc4 23. Nxc4 bxc4 24. Rxe8+ Nxe8 25. Re4 Nf6
            26. Rxc4 Nd5 27. Qe5 Qd7 28. Rg4 f6 29. Qd4 Kh7 30. Re4 Rd8 31. Kh1 Qc7
            32. Qf2 Qb8 33. Ba4 c5 34. Bc6 c4 35. Rxc4 Nb4 36. Bf3 Nd3 37. Qh4 Qxb2
            38. Qg3 Qxa3 39. Rc7 Qf8 40. Ra7 Ne5 41. Rxa5 Qf7 42. Rxe5 fxe5 43. Qxe5 Re8
            44. Qf4 Qf6 45. Bh5 Rf8 46. Bg6+ Kh8 47. Qc7 Qd4 48. Kh2 Ra8 49. Bh5 Qf6
            50. Bg6 Rg8
EOT;

        $this->play($pgn);
    }

    public function testGame23()
    {
        $pgn = <<<EOT
            1. Nf3 d5  2. d4 c6  3. c4 e6  4. Nbd2 Nf6  5. e3 c5  6. b3 Nc6  7. Bb2 cxd4
            8. exd4 Be7  9. Rc1 O-O 10. Bd3 Bd7 11. O-O Nh5 12. Re1 Nf4 13. Bb1 Bd6
            14. g3 Ng6 15. Ne5 Rc8 16. Nxd7 Qxd7 17. Nf3 Bb4 18. Re3 Rfd8 19. h4 Nge7
            20. a3 Ba5 21. b4 Bc7 22. c5 Re8 23. Qd3 g6 24. Re2 Nf5 25. Bc3 h5 26. b5 Nce7
            27. Bd2 Kg7 28. a4 Ra8 29. a5 a6 30. b6 Bb8 31. Bc2 Nc6 32. Ba4 Re7
            33. Bc3 Ne5 34. dxe5 Qxa4 35. Nd4 Nxd4 36. Qxd4 Qd7 37. Bd2 Re8 38. Bg5 Rc8
            39. Bf6+ Kh7 40. c6 bxc6 41. Qc5 Kh6 42. Rb2 Qb7 43. Rb4
EOT;

        $this->play($pgn);
    }
}
