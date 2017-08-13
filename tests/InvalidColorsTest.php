<?php
namespace PGNChess\Tests;

use PGNChess\PGN;
use PGNChess\Board;

class InvalidColorsTest extends \PHPUnit_Framework_TestCase
{
    public function testWrongColor()
    {
        $this->expectException(\InvalidArgumentException::class);
        $board = new Board;
        $this->assertEquals(false, $board->play(PGN::objectizeMove('blue', 'e4')));
    }
}
