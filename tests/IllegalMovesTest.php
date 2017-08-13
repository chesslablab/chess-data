<?php
namespace PGNChess\Tests;

use PGNChess\Board;
use PGNChess\PGN\Converter;
use PGNChess\PGN\Symbol;

class IllegalMovesTest extends \PHPUnit_Framework_TestCase
{
    public function testTurnNormal()
    {
        $board = new Board;
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e4')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_BLACK);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'e5')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
    }


    public function testTurnWithMistakes()
    {
        $board = new Board;
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'e4')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'O-O')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'O-O-O')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e4')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_BLACK);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e5')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_BLACK);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Nf3')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_BLACK);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'e5')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'Nc6')));
    }

    public function testGame01()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'O-O-O')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e5')));

        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'e5')));

        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'Nc6')));

        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra2')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra3')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra4')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra5')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra6')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra7')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra8')));

        // ...
    }
}
