<?php
namespace PGNChess\Tests;

use PGNChess\PGN;

class PGNTest extends \PHPUnit_Framework_TestCase
{
    public function testColorThrowException()
    {
        $this->expectException(\InvalidArgumentException::class);
        PGN::color('green');
    }

    public function testColorIsOk()
    {
        $this->assertEquals(true, PGN::color('w'));
        $this->assertEquals(true, PGN::color('b'));
    }

    public function testSquareThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        PGN::square('a9');
    }

    public function testSquareIsOk()
    {
        $this->assertEquals(PGN::square('e4'), true);
    }

    // move pieces

    public function testMoveUa5ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        PGN::objectizeMove('w', 'Ua5');
    }

    public function testMove3a5ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        PGN::objectizeMove('w', '3a5');
    }

    public function testMovecb3b7()
    {
        $this->expectException(\InvalidArgumentException::class);
        PGN::objectizeMove('w', 'cb3b7');
    }

    public function testMoveBg5()
    {
        $move = 'Bg5';
        $example = (object) [
            'type' => PGN::MOVE_TYPE_PIECE,
            'color' => 'w',
            'identity' => PGN::PIECE_BISHOP,
            'position' => (object) [
                'current' => null,
                'next' =>'g5'
            ]
        ];
        $this->assertEquals(PGN::objectizeMove('w', $move), $example);
    }

    public function testMoveRa5()
    {
        $move = 'Ra5';
        $example = (object) [
            'type' => PGN::MOVE_TYPE_PIECE,
            'color' => 'b',
            'identity' => PGN::PIECE_ROOK,
            'position' => (object) [
                'current' => null,
                'next' => 'a5'
            ]
        ];
        $this->assertEquals(PGN::objectizeMove('b', $move), $example);
    }

    public function testMoveQbb7()
    {
        $move = 'Qbb7';
        $example = (object) [
            'type' => PGN::MOVE_TYPE_PIECE,
            'color' => 'b',
            'identity' => PGN::PIECE_QUEEN,
            'position' => (object) [
                'current' => 'b',
                'next' => 'b7'
            ]
        ];
        $this->assertEquals(PGN::objectizeMove('b', $move), $example);
    }

    public function testMoveNdb4()
    {
        $move = 'Ndb4';
        $example = (object) [
            'type' => PGN::MOVE_TYPE_KNIGHT,
            'color' => 'b',
            'identity' => PGN::PIECE_KNIGHT,
            'position' => (object) [
                'current' => 'd',
                'next' => 'b4'
            ]
        ];
        $this->assertEquals(PGN::objectizeMove('b', $move), $example);
    }

    public function testMoveKg7()
    {
        $move = 'Kg7';
        $example = (object) [
            'type' => PGN::MOVE_TYPE_KING,
            'color' => 'w',
            'identity' => PGN::PIECE_KING,
            'position' => (object) [
                'current' => null,
                'next' => 'g7'
            ]
        ];
        $this->assertEquals(PGN::objectizeMove('w', $move), $example);
    }

    public function testMoveQh8g7()
    {
        $move = 'Qh8g7';
        $example = (object) [
            'type' => PGN::MOVE_TYPE_PIECE,
            'color' => 'b',
            'identity' => PGN::PIECE_QUEEN,
            'position' => (object) [
                'current' => 'h8',
                'next' => 'g7'
            ]
        ];
        $this->assertEquals(PGN::objectizeMove('b', $move), $example);
    }

    // move pawns

    public function testMoveaThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        PGN::objectizeMove('b', 'a');
    }

    public function testMove3ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        PGN::objectizeMove('b', '3');
    }

    public function testMoveK3ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        PGN::objectizeMove('b', 'K3');
    }

    public function testMovec3()
    {
        $move = 'c3';
        $example = (object) [
            'type' => PGN::MOVE_TYPE_PAWN,
            'color' => 'w',
            'identity' => PGN::PIECE_PAWN,
            'position' => (object) [
                'current' => 'c',
                'next' => 'c3'
            ]
        ];
        $this->assertEquals(PGN::objectizeMove('w', $move), $example);
    }

    public function testMoveh4()
    {
        $move = 'h3';
        $example = (object) [
            'type' => PGN::MOVE_TYPE_PAWN,
            'color' => 'w',
            'identity' => PGN::PIECE_PAWN,
            'position' => (object) [
                'current' => 'h',
                'next' => 'h3'
            ]
        ];
        $this->assertEquals(PGN::objectizeMove('w', $move), $example);
    }

    // castling

    public function testMoveShortCastlingThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        PGN::objectizeMove('w', 'a-a');
    }

    public function testMoveLongCastlingThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        PGN::objectizeMove('b', 'c-c-c');
    }

    public function testMoveShortCastling()
    {
        $move = 'O-O';
        $example = (object) [
            'type' => PGN::MOVE_TYPE_KING_CASTLING_SHORT,
            'color' => 'w',
            'identity' => 'K',
            'position' => PGN::CASTLING_SHORT
        ];
        $this->assertEquals(PGN::objectizeMove('w', $move), $example);
    }

    public function testMoveLongCastling()
    {
        $move = 'O-O-O';
        $example = (object) [
            'type' => PGN::MOVE_TYPE_KING_CASTLING_LONG,
            'color' => 'w',
            'identity' => 'K',
            'position' => PGN::CASTLING_LONG
        ];
        $this->assertEquals(PGN::objectizeMove('w', $move), $example);
    }

    // captures

    public function testMoveCaptureThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        PGN::objectizeMove('b', 'Fxa7');
    }

    public function testMoveCapturefxg5()
    {
        $move = 'fxg5';
        $example = (object) [
            'type' => PGN::MOVE_TYPE_PAWN_CAPTURES,
            'color' => 'b',
            'identity' => PGN::PIECE_PAWN,
            'position' => (object) [
                'current' => 'f',
                'next' => 'g5'
            ]
        ];
        $this->assertEquals(PGN::objectizeMove('b', $move), $example);
    }

    public function testMoveCaptureNxe4()
    {
        $move = 'Nxe4';
        $example = (object) [
            'type' => PGN::MOVE_TYPE_KNIGHT_CAPTURES,
            'color' => 'b',
            'identity' => PGN::PIECE_KNIGHT,
            'position' => (object) [
                'current' => null,
                'next' => 'e4'
            ]
        ];
        $this->assertEquals(PGN::objectizeMove('b', $move), $example);
    }

    public function testMoveCaptureQ7xg7()
    {
        $move = 'Q7xg7';
        $example = (object) [
            'type' => PGN::MOVE_TYPE_PIECE_CAPTURES,
            'color' => 'b',
            'identity' => PGN::PIECE_QUEEN,
            'position' => (object) [
                'current' => '7',
                'next' => 'g7'
            ]
        ];
        $this->assertEquals(PGN::objectizeMove('b', $move), $example);
    }
}
