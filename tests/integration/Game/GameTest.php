<?php

namespace PGNChess\Tests\Integration\Game;

use PGNChess\Game;
use PGNChess\Tests\AbstractIntegrationTestCase;

class GameTest extends AbstractIntegrationTestCase
{
    /**
     * @test
     */
    public function play_a3_h6_a4_h5_Ra2()
    {
        $game = new Game;

        $game->play('w', 'a3');
        $game->play('b', 'h6');
        $game->play('w', 'a4');
        $game->play('b', 'h5');
        $game->play('w', 'Ra2');

        $this->assertNull($game->metadata());
    }

    /**
     * @test
     */
    public function play_d4_d5()
    {
        $game = new Game;

        $game->play('w', 'd4');
        $game->play('b', 'd5');

        $this->assertTrue(is_array($game->metadata()));
        $this->assertNotNull($game->metadata());
    }

    /**
     * @test
     */
    public function play_d4_d5_Bf4()
    {
        $game = new Game;

        $game->play('w', 'd4');
        $game->play('b', 'd5');
        $game->play('w', 'Bf4');

        $this->assertTrue(is_array($game->metadata()));
        $this->assertNotNull($game->metadata());
    }
}
