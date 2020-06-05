<?php

namespace PGNChess\Tests\Unit\ArrayOfBoards;

use PGNChess\Board;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PGNChess\Tests\AbstractUnitTestCase;

class LegalMovesTest extends AbstractUnitTestCase
{
    protected static $boards = [];

    protected function setUp()
    {
        self::$boards[0] = new Board();
        self::$boards[1] = new Board();
    }

    /**
     * @test
     */
    public function e4_e5()
    {
        $this->assertEquals(true, self::$boards[0]->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, self::$boards[1]->play(Convert::toObject(Symbol::WHITE, 'e4')));

        $this->assertEquals(true, self::$boards[0]->play(Convert::toObject(Symbol::BLACK, 'e5')));
        $this->assertEquals(true, self::$boards[1]->play(Convert::toObject(Symbol::BLACK, 'e5')));

        $this->assertEquals(true, self::$boards[0]->play(Convert::toObject(Symbol::WHITE, 'd4')));
        $this->assertEquals(true, self::$boards[1]->play(Convert::toObject(Symbol::WHITE, 'd4')));

        $this->assertEquals(true, self::$boards[0]->play(Convert::toObject(Symbol::BLACK, 'exd4')));
        $this->assertEquals(true, self::$boards[1]->play(Convert::toObject(Symbol::BLACK, 'exd4')));
    }
}
