<?php

namespace PGNChess\Tests\Game;

use PGNChess\Game;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PHPUnit\Framework\TestCase;

class PgnGameTest extends TestCase
{
    const EXAMPLES_FOLDER = __DIR__ . '/../../examples';

    /**
     * @test
     */
    public function game_01()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-01.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_02()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-02.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_03()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-03.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_04()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-04.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_05()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-05.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_06()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-06.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_07()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-07.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_08()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-08.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_09()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-09.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_10()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-10.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_11()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-11.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_12()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-12.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_13()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-13.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_14()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-14.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_15()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-15.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_16()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-16.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_17()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-17.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_18()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-18.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_19()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-19.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_20()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-20.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_21()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-21.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_22()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-22.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_23()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-23.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_24()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-24.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_25()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-25.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_26()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-26.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_27()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-27.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_28()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-28.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_29()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-29.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_30()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-30.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_31()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-31.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_32()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-32.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_33()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-33.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_34()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-34.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_35()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-35.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_36()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-36.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_37()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-37.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_38()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-38.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_39()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-39.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_40()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-40.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_41()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-41.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_42()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-42.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_43()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-43.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_44()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-44.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_45()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-45.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_46()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-46.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_47()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-47.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_48()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-48.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_49()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-49.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_50()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-50.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_51()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-51.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_52()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-52.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_53()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-53.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_54()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-54.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_55()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-55.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_56()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-56.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_57()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-57.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_58()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-58.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_59()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-59.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_60()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-60.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_61()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-61.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_62()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-62.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_63()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-63.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_64()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-64.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_65()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-65.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_66()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-66.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_67()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-67.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_68()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-68.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_69()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-69.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_70()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-70.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_71()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-71.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_72()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-72.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_73()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-73.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_74()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-64.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_75()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-75.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_76()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-76.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_77()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-77.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_78()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-78.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_79()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-79.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_80()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-80.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_81()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-81.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_82()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-82.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_83()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-83.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_84()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-84.pgn");

        $this->play($pgn);
    }

    /**
     * @test
     */
    public function game_85()
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER . "/game-85.pgn");

        $this->play($pgn);
    }

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
            $this->assertEquals(true, $game->play(Symbol::WHITE, $whiteMove));
            if (isset($moves[$i][1])) {
                $blackMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][1]));
                $this->assertEquals(true, $game->play(Symbol::BLACK, $blackMove));
            }
        }
    }
}
