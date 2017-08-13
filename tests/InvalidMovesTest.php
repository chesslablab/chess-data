<?php
namespace PGNChess\Tests;

use PGNChess\PGN;
use PGNChess\Board;

class InvalidMovesTest extends \PHPUnit_Framework_TestCase
{
    public function testNumericValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(PGN::objectizeMove(Symbol::COLOR_WHITE, 9)));
    }

    public function test_foo()
    {
        $this->expectException(\InvalidArgumentException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(PGN::objectizeMove(Symbol::COLOR_WHITE, 'foo')));
    }

    public function test_bar()
    {
        $this->expectException(\InvalidArgumentException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(PGN::objectizeMove(Symbol::COLOR_WHITE, 'bar')));
    }

    public function test_e9()
    {
        $this->expectException(\InvalidArgumentException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(PGN::objectizeMove(Symbol::COLOR_WHITE, 'e9')));
    }

    public function test_e10()
    {
        $this->expectException(\InvalidArgumentException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(PGN::objectizeMove(Symbol::COLOR_WHITE, 'e10')));
    }
}
