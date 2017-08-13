<?php
namespace PGNChess\Tests;

use PGNChess\PGN;
use PGNChess\Board;

class IllegalMovesTest extends \PHPUnit_Framework_TestCase
{
    public function testTurnNormal()
    {
        $board = new Board;
        $this->assertEquals($board->getStatus()->turn, PGN::COLOR_WHITE);
        $this->assertEquals(true, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'e4')));
        $this->assertEquals($board->getStatus()->turn, PGN::COLOR_BLACK);
        $this->assertEquals(true, $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'e5')));
        $this->assertEquals($board->getStatus()->turn, PGN::COLOR_WHITE);
    }


    public function testTurnWithMistakes()
    {
        $board = new Board;
        $this->assertEquals($board->getStatus()->turn, PGN::COLOR_WHITE);
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'e4')));
        $this->assertEquals($board->getStatus()->turn, PGN::COLOR_WHITE);
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'O-O')));
        $this->assertEquals($board->getStatus()->turn, PGN::COLOR_WHITE);
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'O-O-O')));
        $this->assertEquals($board->getStatus()->turn, PGN::COLOR_WHITE);
        $this->assertEquals(true, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'e4')));
        $this->assertEquals($board->getStatus()->turn, PGN::COLOR_BLACK);
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'e5')));
        $this->assertEquals($board->getStatus()->turn, PGN::COLOR_BLACK);
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Nf3')));
        $this->assertEquals($board->getStatus()->turn, PGN::COLOR_BLACK);
        $this->assertEquals(true, $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'e5')));
        $this->assertEquals($board->getStatus()->turn, PGN::COLOR_WHITE);
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'Nc6')));
    }

    public function testGame01()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'O-O-O')));
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'e5')));

        $this->assertEquals(true, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'e4')));
        $this->assertEquals(true, $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'e5')));

        $this->assertEquals(true, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'Nc6')));

        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Ra2')));
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Ra3')));
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Ra4')));
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Ra5')));
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Ra6')));
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Ra7')));
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Ra8')));

        // ...
    }
}
