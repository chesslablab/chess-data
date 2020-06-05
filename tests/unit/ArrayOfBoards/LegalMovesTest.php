<?php

namespace PGNChess\Tests\Unit\ArrayOfBoards;

use PGNChess\Board;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PGNChess\Tests\AbstractUnitTestCase;

class LegalMovesTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_e5_d4_exd4_on_two_boards()
    {
        $boards = [];
        $boards[] = new Board();
        $boards[] = new Board();

        $this->assertEquals(true, $boards[0]->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $boards[1]->play(Convert::toObject(Symbol::WHITE, 'e4')));

        $this->assertEquals(true, $boards[0]->play(Convert::toObject(Symbol::BLACK, 'e5')));
        $this->assertEquals(true, $boards[1]->play(Convert::toObject(Symbol::BLACK, 'e5')));

        $this->assertEquals(true, $boards[0]->play(Convert::toObject(Symbol::WHITE, 'd4')));
        $this->assertEquals(true, $boards[1]->play(Convert::toObject(Symbol::WHITE, 'd4')));

        $this->assertEquals(true, $boards[0]->play(Convert::toObject(Symbol::BLACK, 'exd4')));
        $this->assertEquals(true, $boards[1]->play(Convert::toObject(Symbol::BLACK, 'exd4')));
    }

    /**
     * @test
     */
    public function e4_c5_Nf3_d6_on_four_boards()
    {
        $boards = [];
        $boards[] = new Board();
        $boards[] = new Board();
        $boards[] = new Board();
        $boards[] = new Board();

        $this->assertEquals(true, $boards[0]->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $boards[1]->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $boards[2]->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $boards[3]->play(Convert::toObject(Symbol::WHITE, 'e4')));

        $this->assertEquals(true, $boards[0]->play(Convert::toObject(Symbol::BLACK, 'c5')));
        $this->assertEquals(true, $boards[1]->play(Convert::toObject(Symbol::BLACK, 'c5')));
        $this->assertEquals(true, $boards[2]->play(Convert::toObject(Symbol::BLACK, 'c5')));
        $this->assertEquals(true, $boards[3]->play(Convert::toObject(Symbol::BLACK, 'c5')));

        $this->assertEquals(true, $boards[0]->play(Convert::toObject(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $boards[1]->play(Convert::toObject(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $boards[2]->play(Convert::toObject(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $boards[3]->play(Convert::toObject(Symbol::WHITE, 'Nf3')));

        $this->assertEquals(true, $boards[0]->play(Convert::toObject(Symbol::BLACK, 'd6')));
        $this->assertEquals(true, $boards[1]->play(Convert::toObject(Symbol::BLACK, 'd6')));
        $this->assertEquals(true, $boards[2]->play(Convert::toObject(Symbol::BLACK, 'd6')));
        $this->assertEquals(true, $boards[3]->play(Convert::toObject(Symbol::BLACK, 'd6')));
    }
}
