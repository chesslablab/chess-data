<?php
namespace PGNChess\Tests\Piece;

use PGNChess\PGN\Symbol;
use PGNChess\Piece\Pawn;

class PawnTest extends \PHPUnit_Framework_TestCase
{
    public function testScopeWhiteA2()
    {
        $pawn = new Pawn(Symbol::COLOR_WHITE, 'a2');
        $example = (object) [
            'current' => 'a2',
            'scope' => (object) [
                'up' => ['a3', 'a4']
            ],
            'capture' => ['b3']
        ];
        $this->assertEquals($example, $pawn->getPosition());
    }

    public function testScopeWhiteD5()
    {
        $pawn = new Pawn(Symbol::COLOR_WHITE, 'd5');
        $example = (object) [
            'current' => 'd5',
            'scope' => (object) [
                'up' => ['d6']
            ],
            'capture' => ['c6', 'e6']
        ];
        $this->assertEquals($example, $pawn->getPosition());
    }

    public function testScopeWhiteF7()
    {
        $pawn = new Pawn(Symbol::COLOR_WHITE, 'f7');
        $example = (object) [
            'current' => 'f7',
            'scope' => (object) [
                'up' => ['f8']
            ],
            'capture' => ['e8', 'g8']
        ];
        $this->assertEquals($example, $pawn->getPosition());
    }

    public function testScopeWhiteF8()
    {
        $pawn = new Pawn(Symbol::COLOR_WHITE, 'f8');
        $example = (object) [
            'current' => 'f8',
            'scope' => (object) [
                'up' => []
            ],
            'capture' => []
        ];
        $this->assertEquals($example, $pawn->getPosition());
    }

    public function testScopeBlackA2()
    {
        $pawn = new Pawn(Symbol::COLOR_BLACK, 'a2');
        $example = (object) [
            'current' => 'a2',
            'scope' => (object) [
                'up' => ['a1']
            ],
            'capture' => ['b1']
        ];
        $this->assertEquals($example, $pawn->getPosition());
    }

    public function testScopeBlackD5()
    {
        $pawn = new Pawn(Symbol::COLOR_BLACK, 'd5');
        $example = (object) [
            'current' => 'd5',
            'scope' => (object) [
                'up' => ['d4']
            ],
            'capture' => ['c4', 'e4']
        ];
        $this->assertEquals($example, $pawn->getPosition());
    }
}
