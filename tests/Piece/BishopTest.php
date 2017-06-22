<?php
namespace PGNChess\Tests\Piece;

use PGNChess\PGN;
use PGNChess\Piece\Bishop;

class BishopTest extends \PHPUnit_Framework_TestCase
{
    public function testScopeA2()
    {
        $bishop = new Bishop(PGN::COLOR_WHITE, 'a2');
        $example = (object) [
            'upLeft' => [],
            'upRight' => ['b3', 'c4', 'd5', 'e6', 'f7', 'g8'],
            'bottomLeft' => [],
            'bottomRight' => ['b1']
        ];
        $this->assertEquals($example, $bishop->getPosition()->scope);
    }

    public function testScopeD5()
    {
        $bishop = new Bishop(PGN::COLOR_WHITE, 'd5');
        $example = (object) [
            'upLeft' => ['c6', 'b7', 'a8'],
            'upRight' => ['e6', 'f7', 'g8'],
            'bottomLeft' => ['c4', 'b3', 'a2'],
            'bottomRight' => ['e4', 'f3', 'g2', 'h1']
        ];
        $this->assertEquals($example, $bishop->getPosition()->scope);
    }

    public function testScopeA8()
    {
        $bishop = new Bishop(PGN::COLOR_WHITE, 'a8');
        $example = (object) [
            'upLeft' => [],
            'upRight' => [],
            'bottomLeft' => [],
            'bottomRight' => ['b7', 'c6', 'd5', 'e4', 'f3', 'g2', 'h1']
        ];
        $this->assertEquals($example, $bishop->getPosition()->scope);
    }

}
