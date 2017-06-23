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
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->b, 'b1');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->c, 'c1');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->d, 'd1');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->move->current, 'e1');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->move->next, 'c1');
        $this->assertEquals($castlingInfo->{PGN::PIECE_ROOK}->{PGN::CASTLING_LONG}->move->current, 'a1');
        $this->assertEquals($castlingInfo->{PGN::PIECE_ROOK}->{PGN::CASTLING_LONG}->move->next, 'd1');
    }

    public function testGetBlackLongCastling()
    {
        $king = new King(PGN::COLOR_BLACK, 'e8');
        $castlingInfo = $king->getCastlingInfo();
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->b, 'b8');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->c, 'c8');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->d, 'd8');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->move->current, 'e8');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->move->next, 'c8');
        $this->assertEquals($castlingInfo->{PGN::PIECE_ROOK}->{PGN::CASTLING_LONG}->move->current, 'a8');
        $this->assertEquals($castlingInfo->{PGN::PIECE_ROOK}->{PGN::CASTLING_LONG}->move->next, 'd8');
    }

    public function testGetWhiteShortCastling()
    {
        $king = new King(PGN::COLOR_WHITE, 'e1');
        $castlingInfo = $king->getCastlingInfo();
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->freeSquares->f, 'f1');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->freeSquares->g, 'g1');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->move->current, 'e1');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->move->next, 'g1');
        $this->assertEquals($castlingInfo->{PGN::PIECE_ROOK}->{PGN::CASTLING_SHORT}->move->current, 'h1');
        $this->assertEquals($castlingInfo->{PGN::PIECE_ROOK}->{PGN::CASTLING_SHORT}->move->next, 'f1');
    }

    public function testGetBlackShortCastling()
    {
        $king = new King(PGN::COLOR_BLACK, 'e8');
        $castlingInfo = $king->getCastlingInfo();
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->freeSquares->f, 'f8');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->freeSquares->g, 'g8');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->move->current, 'e8');
        $this->assertEquals($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->move->next, 'g8');
        $this->assertEquals($castlingInfo->{PGN::PIECE_ROOK}->{PGN::CASTLING_SHORT}->move->current, 'h8');
        $this->assertEquals($castlingInfo->{PGN::PIECE_ROOK}->{PGN::CASTLING_SHORT}->move->next, 'f8');
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
