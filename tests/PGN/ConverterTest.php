<?php
namespace PGNChess\Tests\PGN;

use PGNChess\Square\Castling;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Move;
use PGNChess\PGN\Symbol;

class ConvertTest extends \PHPUnit_Framework_TestCase
{
    // throw exceptions

    public function testMoveUa5ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Convert::toObject(Symbol::WHITE, 'Ua5');
    }

    public function testMove3a5ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Convert::toObject(Symbol::BLACK, '3a5');
    }

    public function testMovecb3b7ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Convert::toObject(Symbol::WHITE, 'cb3b7');
    }

    public function testMoveShortCastlingThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Convert::toObject(Symbol::BLACK, 'a-a');
    }

    public function testMoveLongCastlingThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Convert::toObject(Symbol::WHITE, 'c-c-c');
    }

    public function testMoveaThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Convert::toObject(Symbol::BLACK, 'a');
    }

    public function testMove3ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Convert::toObject(Symbol::WHITE, 3);
    }

    public function testMoveK3ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Convert::toObject(Symbol::BLACK, 'K3');
    }

    public function testMoveCaptureThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Convert::toObject(Symbol::WHITE, 'Fxa7');
    }

    // convert pieces' moves

    public function testMoveBg5()
    {
        $move = 'Bg5';
        $example = (object) [
            'pgn' => 'Bg5',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PIECE,
            'color' => Symbol::WHITE,
            'identity' => Symbol::BISHOP,
            'position' => (object) [
                'current' => null,
                'next' =>'g5'
            ]
        ];
        $this->assertEquals(Convert::toObject(Symbol::WHITE, $move), $example);
    }

    public function testMoveRa5()
    {
        $move = 'Ra5';
        $example = (object) [
            'pgn' => 'Ra5',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PIECE,
            'color' => Symbol::BLACK,
            'identity' => Symbol::ROOK,
            'position' => (object) [
                'current' => null,
                'next' => 'a5'
            ]
        ];
        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
    }

    public function testMoveQbb7()
    {
        $move = 'Qbb7';
        $example = (object) [
            'pgn' => 'Qbb7',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PIECE,
            'color' => Symbol::BLACK,
            'identity' => Symbol::QUEEN,
            'position' => (object) [
                'current' => Symbol::BLACK,
                'next' => 'b7'
            ]
        ];
        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
    }

    public function testMoveNdb4()
    {
        $move = 'Ndb4';
        $example = (object) [
            'pgn' => 'Ndb4',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::KNIGHT,
            'color' => Symbol::BLACK,
            'identity' => Symbol::KNIGHT,
            'position' => (object) [
                'current' => 'd',
                'next' => 'b4'
            ]
        ];
        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
    }

    public function testMoveKg7()
    {
        $move = 'Kg7';
        $example = (object) [
            'pgn' => 'Kg7',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::KING,
            'color' => Symbol::WHITE,
            'identity' => Symbol::KING,
            'position' => (object) [
                'current' => null,
                'next' => 'g7'
            ]
        ];
        $this->assertEquals(Convert::toObject(Symbol::WHITE, $move), $example);
    }

    public function testMoveQh8g7()
    {
        $move = 'Qh8g7';
        $example = (object) [
            'pgn' => 'Qh8g7',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PIECE,
            'color' => Symbol::BLACK,
            'identity' => Symbol::QUEEN,
            'position' => (object) [
                'current' => 'h8',
                'next' => 'g7'
            ]
        ];
        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
    }

    // convert pawns' moves

    public function testMovec3()
    {
        $move = 'c3';
        $example = (object) [
            'pgn' => 'c3',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PAWN,
            'color' => Symbol::WHITE,
            'identity' => Symbol::PAWN,
            'position' => (object) [
                'current' => 'c',
                'next' => 'c3'
            ]
        ];
        $this->assertEquals(Convert::toObject(Symbol::WHITE, $move), $example);
    }

    public function testMoveh4()
    {
        $move = 'h3';
        $example = (object) [
            'pgn' => 'h3',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PAWN,
            'color' => Symbol::WHITE,
            'identity' => Symbol::PAWN,
            'position' => (object) [
                'current' => 'h',
                'next' => 'h3'
            ]
        ];
        $this->assertEquals(Convert::toObject(Symbol::WHITE, $move), $example);
    }

    // castling

    public function testMoveShortCastling()
    {
        $move = 'O-O';
        $example = (object) [
            'pgn' => 'O-O',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::KING_CASTLING_SHORT,
            'color' => Symbol::WHITE,
            'identity' => 'K',
            'position' => Castling::info(Symbol::WHITE)->{Symbol::KING}->{Symbol::CASTLING_SHORT}->position
        ];
        $this->assertEquals(Convert::toObject(Symbol::WHITE, $move), $example);
    }

    public function testMoveLongCastling()
    {
        $move = 'O-O-O';
        $example = (object) [
            'pgn' => 'O-O-O',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::KING_CASTLING_LONG,
            'color' => Symbol::WHITE,
            'identity' => 'K',
            'position' => Castling::info(Symbol::WHITE)->{Symbol::KING}->{Symbol::CASTLING_LONG}->position
        ];
        $this->assertEquals(Convert::toObject(Symbol::WHITE, $move), $example);
    }

    // captures

    public function testMoveCapturefxg5()
    {
        $move = 'fxg5';
        $example = (object) [
            'pgn' => 'fxg5',
            'isCapture' => true,
            'isCheck' => false,
            'type' => Move::PAWN_CAPTURES,
            'color' => Symbol::BLACK,
            'identity' => Symbol::PAWN,
            'position' => (object) [
                'current' => 'f',
                'next' => 'g5'
            ]
        ];
        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
    }

    public function testMoveCaptureNxe4()
    {
        $move = 'Nxe4';
        $example = (object) [
            'pgn' => 'Nxe4',
            'isCapture' => true,
            'isCheck' => false,
            'type' => Move::KNIGHT_CAPTURES,
            'color' => Symbol::BLACK,
            'identity' => Symbol::KNIGHT,
            'position' => (object) [
                'current' => null,
                'next' => 'e4'
            ]
        ];
        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
    }

    public function testMoveCaptureQ7xg7()
    {
        $move = 'Q7xg7';
        $example = (object) [
            'pgn' => 'Q7xg7',
            'isCapture' => true,
            'isCheck' => false,
            'type' => Move::PIECE_CAPTURES,
            'color' => Symbol::BLACK,
            'identity' => Symbol::QUEEN,
            'position' => (object) [
                'current' => '7',
                'next' => 'g7'
            ]
        ];
        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
    }
}
