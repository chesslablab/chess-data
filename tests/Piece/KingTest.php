<?php
namespace PGNChess\Tests\Piece;

use PGNChess\PGN\Symbol;
use PGNChess\Piece\King;

class KingTest extends \PHPUnit_Framework_TestCase
{
    public function testScopeA2()
    {
        $king = new King(Symbol::WHITE, 'a2');
        $example = (object) [
            'up' => 'a3',
            'bottom' => 'a1',
            'right' => 'b2',
            'upRight' => 'b3',
            'bottomRight' => 'b1'
        ];
        $position = $king->getPosition();
        $this->assertEquals($example, $position->scope);
    }

    public function testScopeD5()
    {
        $king = new King(Symbol::WHITE, 'd5');
        $example = (object) [
            'up' => 'd6',
            'bottom' => 'd4',
            'left' => 'c5',
            'right' => 'e5',
            'upLeft' => 'c6',
            'upRight' => 'e6',
            'bottomLeft' => 'c4',
            'bottomRight' => 'e4'
        ];
        $position = $king->getPosition();
        $this->assertEquals($example, $position->scope);
    }

}
