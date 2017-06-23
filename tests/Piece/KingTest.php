<?php
namespace PGNChess\Tests\Piece;

use PGNChess\PGN;
use PGNChess\Piece\King;

class KingTest extends \PHPUnit_Framework_TestCase
{
    public function testGetWhiteLongCastling()
    {
        $king = new King(PGN::COLOR_WHITE, 'e1');
        $castlingInfo = $king->getCastlingInfo();
        $this->assertEquals($castlingInfo->K->long->freeSquares->b, 'b1');
        $this->assertEquals($castlingInfo->K->long->freeSquares->c, 'c1');
        $this->assertEquals($castlingInfo->K->long->freeSquares->d, 'd1');
        $this->assertEquals($castlingInfo->K->long->move->current, 'e1');
        $this->assertEquals($castlingInfo->K->long->move->next, 'c1');
        $this->assertEquals($castlingInfo->R->long->move->current, 'a1');
        $this->assertEquals($castlingInfo->R->long->move->next, 'd1');
    }

    public function testGetBlackLongCastling()
    {
        $king = new King(PGN::COLOR_BLACK, 'e8');
        $castlingInfo = $king->getCastlingInfo();
        $this->assertEquals($castlingInfo->K->long->freeSquares->b, 'b8');
        $this->assertEquals($castlingInfo->K->long->freeSquares->c, 'c8');
        $this->assertEquals($castlingInfo->K->long->freeSquares->d, 'd8');
        $this->assertEquals($castlingInfo->K->long->move->current, 'e8');
        $this->assertEquals($castlingInfo->K->long->move->next, 'c8');
        $this->assertEquals($castlingInfo->R->long->move->current, 'a8');
        $this->assertEquals($castlingInfo->R->long->move->next, 'd8');
    }

    public function testGetWhiteShortCastling()
    {
        $king = new King(PGN::COLOR_WHITE, 'e1');
        $castlingInfo = $king->getCastlingInfo();
        $this->assertEquals($castlingInfo->K->short->freeSquares->f, 'f1');
        $this->assertEquals($castlingInfo->K->short->freeSquares->g, 'g1');
        $this->assertEquals($castlingInfo->K->short->move->current, 'e1');
        $this->assertEquals($castlingInfo->K->short->move->next, 'g1');
        $this->assertEquals($castlingInfo->R->short->move->current, 'h1');
        $this->assertEquals($castlingInfo->R->short->move->next, 'f1');
    }

    public function testGetBlackShortCastling()
    {
        $king = new King(PGN::COLOR_BLACK, 'e8');
        $castlingInfo = $king->getCastlingInfo();
        $this->assertEquals($castlingInfo->K->short->freeSquares->f, 'f8');
        $this->assertEquals($castlingInfo->K->short->freeSquares->g, 'g8');
        $this->assertEquals($castlingInfo->K->short->move->current, 'e8');
        $this->assertEquals($castlingInfo->K->short->move->next, 'g8');
        $this->assertEquals($castlingInfo->R->short->move->current, 'h8');
        $this->assertEquals($castlingInfo->R->short->move->next, 'f8');
    }

    public function testScopeA2()
    {
        $king = new King(PGN::COLOR_WHITE, 'a2');
        $example = (object) [
            'up' => 'a3',
            'bottom' => 'a1',
            'left' => null,
            'right' => 'b2',
            'upLeft' => null,
            'upRight' => 'b3',
            'bottomLeft' => null,
            'bottomRight' => 'b1'
        ];
        $position = $king->getPosition();
        $this->assertEquals($example, $position->scope);
    }

    public function testScopeD5()
    {
        $king = new King(PGN::COLOR_WHITE, 'd5');
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
