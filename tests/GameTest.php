<?php
namespace PGNChess\Tests;

use PGNChess\Game;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;

class GameTest extends \PHPUnit_Framework_TestCase
{
    const EXAMPLES_FOLDER = __DIR__ . '/../examples';
    
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
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-01.pgn");
        
        $this->play($pgn);
    }

    public function testGame02()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-02.pgn");
        
        $this->play($pgn);
    }

    public function testGame03()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-03.pgn");
        
        $this->play($pgn);
    }

    public function testGame04()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-04.pgn");
        
        $this->play($pgn);
    }

    public function testGame05()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-05.pgn");
        
        $this->play($pgn);
    }

    public function testGame06()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-06.pgn");
        
        $this->play($pgn);
    }

    public function testGame07()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-07.pgn");
        
        $this->play($pgn);
    }

    public function testGame08()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-08.pgn");
        
        $this->play($pgn);
    }

    public function testGame09()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-09.pgn");

        $this->play($pgn);
    }

    public function testGame10()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-10.pgn");

        $this->play($pgn);
    }

    public function testGame11()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-11.pgn");
        
        $this->play($pgn);
    }

    public function testGame12()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-12.pgn");
        
        $this->play($pgn);
    }

    public function testGame13()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-13.pgn");
        
        $this->play($pgn);
    }

    public function testGame14()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-14.pgn");
        
        $this->play($pgn);
    }

    public function testGame15()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-15.pgn");

        $this->play($pgn);
    }

    public function testGame16()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-16.pgn");

        $this->play($pgn);
    }

    public function testGame17()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-17.pgn");

        $this->play($pgn);
    }

    public function testGame18()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-18.pgn");

        $this->play($pgn);
    }

    public function testGame19()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-19.pgn");

        $this->play($pgn);
    }

    public function testGame20()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-20.pgn");

        $this->play($pgn);
    }

    public function testGame21()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-21.pgn");

        $this->play($pgn);
    }

    public function testGame22()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-22.pgn");

        $this->play($pgn);
    }

    public function testGame23()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-23.pgn");

        $this->play($pgn);
    }

    public function testGame24()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-24.pgn");

        $this->play($pgn);
    }

    public function testGame25()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-25.pgn");

        $this->play($pgn);
    }

    public function testGame26()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-26.pgn");

        $this->play($pgn);
    }

    public function testGame27()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-27.pgn");

        $this->play($pgn);
    }

    public function testGame28()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-28.pgn");

        $this->play($pgn);
    }

    public function testGame29()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-29.pgn");

        $this->play($pgn);
    }

    public function testGame30()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-30.pgn");

        $this->play($pgn);
    }

    public function testGame31()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-31.pgn");

        $this->play($pgn);
    }

    public function testGame32()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-32.pgn");

        $this->play($pgn);
    }

    public function testGame33()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-33.pgn");

        $this->play($pgn);
    }

    public function testGame34()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-34.pgn");

        $this->play($pgn);
    }

    public function testGame35()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-35.pgn");

        $this->play($pgn);
    }

    public function testGame36()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-36.pgn");

        $this->play($pgn);
    }

    public function testGame37()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-37.pgn");

        $this->play($pgn);
    }

    public function testGame38()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-38.pgn");

        $this->play($pgn);
    }

    public function testGame39()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-39.pgn");

        $this->play($pgn);
    }

    public function testGame40()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-40.pgn");

        $this->play($pgn);
    }

    public function testGame41()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-41.pgn");

        $this->play($pgn);
    }
    
    public function testGame42()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-42.pgn");

        $this->play($pgn);
    }
    
    public function testGame43()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-43.pgn");

        $this->play($pgn);
    }
    
    public function testGame44()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-44.pgn");

        $this->play($pgn);
    }
    
    public function testGame45()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-45.pgn");

        $this->play($pgn);
    }
    
    public function testGame46()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-46.pgn");

        $this->play($pgn);
    }
    
    public function testGame47()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-47.pgn");

        $this->play($pgn);
    }
    
    public function testGame48()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-48.pgn");

        $this->play($pgn);
    }
    
    public function testGame49()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-49.pgn");

        $this->play($pgn);
    }
    
    public function testGame50()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-50.pgn");

        $this->play($pgn);
    }
    
    public function testGame51()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-51.pgn");

        $this->play($pgn);
    }
    
    public function testGame52()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-52.pgn");

        $this->play($pgn);
    }
    
    public function testGame53()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-53.pgn");

        $this->play($pgn);
    }
    
    public function testGame54()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-54.pgn");

        $this->play($pgn);
    }
    
    public function testGame55()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-55.pgn");

        $this->play($pgn);
    }
    
    public function testGame56()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-56.pgn");

        $this->play($pgn);
    }
    
    public function testGame57()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-57.pgn");

        $this->play($pgn);
    }
    
    public function testGame58()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-58.pgn");

        $this->play($pgn);
    }
    
    public function testGame59()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-59.pgn");

        $this->play($pgn);
    }
    
    public function testGame60()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-60.pgn");

        $this->play($pgn);
    }
    
    public function testGame61()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-61.pgn");

        $this->play($pgn);
    }
    
    public function testGame62()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-62.pgn");

        $this->play($pgn);
    }
    
    public function testGame63()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-63.pgn");

        $this->play($pgn);
    }
    
    public function testGame64()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-64.pgn");

        $this->play($pgn);
    }
    
    public function testGame65()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-65.pgn");

        $this->play($pgn);
    }
    
    public function testGame66()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-66.pgn");

        $this->play($pgn);
    }
    
    public function testGame67()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-67.pgn");

        $this->play($pgn);
    }
    
    public function testGame68()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-68.pgn");

        $this->play($pgn);
    }
    
    public function testGame69()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-69.pgn");

        $this->play($pgn);
    }
    
    public function testGame70()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-70.pgn");

        $this->play($pgn);
    }
    
    public function testGame71()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-71.pgn");

        $this->play($pgn);
    }
    
    public function testGame72()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-72.pgn");

        $this->play($pgn);
    }
    
    public function testGame73()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-73.pgn");

        $this->play($pgn);
    }
    
    public function testGame74()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-64.pgn");

        $this->play($pgn);
    }
    
    public function testGame75()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-75.pgn");

        $this->play($pgn);
    }
    
    public function testGame76()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-76.pgn");

        $this->play($pgn);
    }
    
    public function testGame77()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-77.pgn");

        $this->play($pgn);
    }
    
    public function testGame78()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-78.pgn");

        $this->play($pgn);
    }
    
    public function testGame79()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-79.pgn");

        $this->play($pgn);
    }
    
    public function testGame80()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-80.pgn");

        $this->play($pgn);
    }
    
    public function testGame81()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-81.pgn");

        $this->play($pgn);
    }
    
    public function testGame82()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-82.pgn");

        $this->play($pgn);
    }
    
    public function testGame83()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-83.pgn");

        $this->play($pgn);
    }
    
    public function testGame84()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-84.pgn");

        $this->play($pgn);
    }
    
    public function testGame85()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-85.pgn");

        $this->play($pgn);
    }
    
}
