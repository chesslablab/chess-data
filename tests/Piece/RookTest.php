<?php
namespace PGNChess\Tests\Piece;

use PGNChess\PGN\Symbol;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Type\RookType;

class RookTest extends \PHPUnit_Framework_TestCase
{
    public function testScope_a2()
    {
        $rook = new Rook(Symbol::WHITE, 'a2', RookType::PROMOTED);
        $example = (object) [
            'up' => ['a3', 'a4', 'a5', 'a6', 'a7', 'a8'],
            'bottom' => ['a1'],
            'left' => [],
            'right' => ['b2', 'c2', 'd2', 'e2', 'f2', 'g2', 'h2']
        ];
        $this->assertEquals($example, $rook->getPosition()->scope);
    }

    public function testScope_d5()
    {
        $rook = new Rook(Symbol::WHITE, 'd5', RookType::PROMOTED);
        $example = (object) [
            'up' => ['d6', 'd7', 'd8'],
            'bottom' => ['d4', 'd3', 'd2', 'd1'],
            'left' => ['c5', 'b5', 'a5'],
            'right' => ['e5', 'f5', 'g5', 'h5']
        ];
        $this->assertEquals($example, $rook->getPosition()->scope);
    }
}
