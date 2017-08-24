<?php
namespace PGNChess\Tests\Piece;

use PGNChess\PGN\Symbol;
use PGNChess\Piece\Queen;

class QueenTest extends \PHPUnit_Framework_TestCase
{
    public function testScope_a2()
    {
        $queen = new Queen(Symbol::WHITE, 'a2');
        $scope = (object) [
            'up' => ['a3', 'a4', 'a5', 'a6', 'a7', 'a8'],
            'bottom' => ['a1'],
            'left' => [],
            'right' => ['b2', 'c2', 'd2', 'e2', 'f2', 'g2', 'h2'],
            'upLeft' => [],
            'upRight' => ['b3', 'c4', 'd5', 'e6', 'f7', 'g8'],
            'bottomLeft' => [],
            'bottomRight' => ['b1']
        ];
        $this->assertEquals($scope, $queen->getScope());
    }

    public function testScope_d5()
    {
        $queen = new Queen(Symbol::WHITE, 'd5');
        $scope = (object) [
            'up' => ['d6', 'd7', 'd8'],
            'bottom' => ['d4', 'd3', 'd2', 'd1'],
            'left' => ['c5', 'b5', 'a5'],
            'right' => ['e5', 'f5', 'g5', 'h5'],
            'upLeft' => ['c6', 'b7', 'a8'],
            'upRight' => ['e6', 'f7', 'g8'],
            'bottomLeft' => ['c4', 'b3', 'a2'],
            'bottomRight' => ['e4', 'f3', 'g2', 'h1']
        ];
        $this->assertEquals($scope, $queen->getScope());
    }
}
