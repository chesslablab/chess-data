<?php
namespace PGNChess\Tests\Piece;

use PGNChess\PGN\Symbol;
use PGNChess\Piece\Knight;

class KnightTest extends \PHPUnit_Framework_TestCase
{
    public function testScopeD4()
    {
        $knight = new Knight(Symbol::WHITE, 'd4');
        $example = [
            'c6',
            'b5',
            'b3',
            'c2',
            'e2',
            'f3',
            'f5',
            'e6'
        ];
        $this->assertEquals($example, $knight->getPosition()->scope->jumps);
    }

    public function testScopeH1()
    {
        $knight = new Knight(Symbol::WHITE, 'h1');
        $example = [
            'g3',
            'f2'
        ];
        $this->assertEquals($example, $knight->getPosition()->scope->jumps);
    }

    public function testScopeB1()
    {
        $knight = new Knight(Symbol::WHITE, 'b1');
        $example = [
            'a3',
            'd2',
            'c3'
        ];
        $this->assertEquals($example, $knight->getPosition()->scope->jumps);
    }
}
