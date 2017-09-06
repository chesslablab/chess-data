<?php
namespace PGNChess\Tests;

use PGNChess\Game;

class GameTest extends \PHPUnit_Framework_TestCase
{

    public function testCanAccessEmptySquare()
    {
        $game = new Game();
        $piece = $game->piece('e3');

        $this->assertNull($piece->color);
        $this->assertNull($piece->identity);
        $this->assertEquals('e3', $piece->position);
        $this->assertEquals([], $piece->moves);
    }
}