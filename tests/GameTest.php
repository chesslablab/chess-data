<?php
namespace PGNChess\Tests;

use PGNChess\Game;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;

class GameTest extends \PHPUnit_Framework_TestCase
{
    const EXAMPLES_PGN_FOLDER = '/../examples/pgn/';
    
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
        include self::EXAMPLES_PGN_FOLDER . 'pgn-01.php';
        
        $this->play($pgn);
    }

    public function testGame02()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-02.php';
        
        $this->play($pgn);
    }

    public function testGame03()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-03.php'; 
        
        $this->play($pgn);
    }

    public function testGame04()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-04.php'; 
        
        $this->play($pgn);
    }

    public function testGame05()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-05.php'; 
        
        $this->play($pgn);
    }

    public function testGame06()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-06.php';
        
        $this->play($pgn);
    }

    public function testGame07()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-07.php';
        
        $this->play($pgn);
    }

    public function testGame08()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-08.php';
        
        $this->play($pgn);
    }

    public function testGame09()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-09.php';

        $this->play($pgn);
    }

    public function testGame10()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-10.php';

        $this->play($pgn);
    }

    public function testGame11()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-11.php';
        
        $this->play($pgn);
    }

    public function testGame12()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-12.php';
        
        $this->play($pgn);
    }

    public function testGame13()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-13.php';
        
        $this->play($pgn);
    }

    public function testGame14()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-14.php';
        
        $this->play($pgn);
    }

    public function testGame15()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-15.php';

        $this->play($pgn);
    }

    public function testGame16()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-16.php';

        $this->play($pgn);
    }

    public function testGame17()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-17.php';

        $this->play($pgn);
    }

    public function testGame18()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-18.php';

        $this->play($pgn);
    }

    public function testGame19()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-19.php';

        $this->play($pgn);
    }

    public function testGame20()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-20.php';

        $this->play($pgn);
    }

    public function testGame21()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-21.php';

        $this->play($pgn);
    }

    public function testGame22()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-22.php';

        $this->play($pgn);
    }

    public function testGame23()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-23.php';

        $this->play($pgn);
    }

    public function testGame24()
    {
    include self::EXAMPLES_PGN_FOLDER . 'pgn-24.php';

        $this->play($pgn);
    }

    public function testGame25()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-25.php';

        $this->play($pgn);
    }

    public function testGame26()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-26.php';

        $this->play($pgn);
    }

    public function testGame27()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-27.php';

        $this->play($pgn);
    }

    public function testGame28()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-28.php';

        $this->play($pgn);
    }

    public function testGame29()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-29.php';

        $this->play($pgn);
    }

    public function testGame30()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-30.php';

        $this->play($pgn);
    }

    public function testGame31()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-31.php';

        $this->play($pgn);
    }

    public function testGame32()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-32.php';

        $this->play($pgn);
    }

    public function testGame33()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-33.php';

        $this->play($pgn);
    }

    public function testGame34()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-34.php';

        $this->play($pgn);
    }

    public function testGame35()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-35.php';

        $this->play($pgn);
    }

    public function testGame36()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-36.php';

        $this->play($pgn);
    }

    public function testGame37()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-37.php';

        $this->play($pgn);
    }

    public function testGame38()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-38.php';

        $this->play($pgn);
    }

    public function testGame39()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-39.php';

        $this->play($pgn);
    }

    public function testGame40()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-40.php';

        $this->play($pgn);
    }

    public function testGame41()
    {
        include self::EXAMPLES_PGN_FOLDER . 'pgn-41.php';

        $this->play($pgn);
    }
}
