<?php

namespace PGNChess\Tests\Integration\Board;

use PGNChess\Board;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PGNChess\Tests\AbstractIntegrationTestCase;

class StatusTest extends AbstractIntegrationTestCase
{
    /**
     * @test
     */
    public function play_a3_h6_a4_h5_Ra2()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'a3'));
        $board->play(Convert::toObject(Symbol::BLACK, 'h6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'a4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'h5'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Ra2'));

        $this->assertNull($board->metadata());
    }

    /**
     * @test
     */
    public function play_d4_d5()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'd4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'd5'));

        $this->assertTrue(is_array($board->metadata()));
        $this->assertNotNull($board->metadata());
    }

    /**
     * @test
     */
    public function play_d4_d5_Bf4()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'd4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'd5'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Bf4'));

        $this->assertTrue(is_array($board->metadata()));
        $this->assertNotNull($board->metadata());
    }
}
