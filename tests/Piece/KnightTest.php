<?php
namespace PGNChess\Tests\Piece;

use PGNChess\PGN\Symbol;
use PGNChess\Piece\Knight;

class KnightTest extends \PHPUnit_Framework_TestCase
{
    public function testScope_d4()
    {
        $knight = new Knight(Symbol::WHITE, 'd4');
        $jumps = [
            'c6',
            'b5',
            'b3',
            'c2',
            'e2',
            'f3',
            'f5',
            'e6'
        ];
        $this->assertEquals($jumps, $knight->getScope()->jumps);
    }

    public function testScope_h1()
    {
        $knight = new Knight(Symbol::WHITE, 'h1');
        $jumps = [
            'g3',
            'f2'
        ];
        $this->assertEquals($jumps, $knight->getScope()->jumps);
    }

    public function testScope_b1()
    {
        $knight = new Knight(Symbol::WHITE, 'b1');
        $jumps = [
            'a3',
            'd2',
            'c3'
        ];
        $this->assertEquals($jumps, $knight->getScope()->jumps);
    }
}
