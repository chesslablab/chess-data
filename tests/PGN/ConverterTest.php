<?php
namespace PGNChess\Tests\PGN;

use PGNChess\Castling;
use PGNChess\PGN\Converter;
use PGNChess\PGN\Move;
use PGNChess\PGN\Symbol;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    // throw exceptions

    public function testMoveUa5ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Converter::toObject('w', 'Ua5');
    }

    public function testMove3a5ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Converter::toObject('b', '3a5');
    }

    public function testMovecb3b7ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Converter::toObject('w', 'cb3b7');
    }

    public function testMoveShortCastlingThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Converter::toObject('b', 'a-a');
    }

    public function testMoveLongCastlingThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Converter::toObject('w', 'c-c-c');
    }

    public function testMoveaThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Converter::toObject('b', 'a');
    }

    public function testMove3ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Converter::toObject('w', 3);
    }

    public function testMoveK3ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Converter::toObject('b', 'K3');
    }

    public function testMoveCaptureThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Converter::toObject('w', 'Fxa7');
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
            'color' => 'w',
            'identity' => Symbol::PIECE_BISHOP,
            'position' => (object) [
                'current' => null,
                'next' =>'g5'
            ]
        ];
        $this->assertEquals(Converter::toObject('w', $move), $example);
    }

    public function testMoveRa5()
    {
        $move = 'Ra5';
        $example = (object) [
            'pgn' => 'Ra5',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PIECE,
            'color' => 'b',
            'identity' => Symbol::PIECE_ROOK,
            'position' => (object) [
                'current' => null,
                'next' => 'a5'
            ]
        ];
        $this->assertEquals(Converter::toObject('b', $move), $example);
    }

    public function testMoveQbb7()
    {
        $move = 'Qbb7';
        $example = (object) [
            'pgn' => 'Qbb7',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PIECE,
            'color' => 'b',
            'identity' => Symbol::PIECE_QUEEN,
            'position' => (object) [
                'current' => 'b',
                'next' => 'b7'
            ]
        ];
        $this->assertEquals(Converter::toObject('b', $move), $example);
    }

    public function testMoveNdb4()
    {
        $move = 'Ndb4';
        $example = (object) [
            'pgn' => 'Ndb4',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::KNIGHT,
            'color' => 'b',
            'identity' => Symbol::PIECE_KNIGHT,
            'position' => (object) [
                'current' => 'd',
                'next' => 'b4'
            ]
        ];
        $this->assertEquals(Converter::toObject('b', $move), $example);
    }

    public function testMoveKg7()
    {
        $move = 'Kg7';
        $example = (object) [
            'pgn' => 'Kg7',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::KING,
            'color' => 'w',
            'identity' => Symbol::PIECE_KING,
            'position' => (object) [
                'current' => null,
                'next' => 'g7'
            ]
        ];
        $this->assertEquals(Converter::toObject('w', $move), $example);
    }

    public function testMoveQh8g7()
    {
        $move = 'Qh8g7';
        $example = (object) [
            'pgn' => 'Qh8g7',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PIECE,
            'color' => 'b',
            'identity' => Symbol::PIECE_QUEEN,
            'position' => (object) [
                'current' => 'h8',
                'next' => 'g7'
            ]
        ];
        $this->assertEquals(Converter::toObject('b', $move), $example);
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
            'color' => 'w',
            'identity' => Symbol::PIECE_PAWN,
            'position' => (object) [
                'current' => 'c',
                'next' => 'c3'
            ]
        ];
        $this->assertEquals(Converter::toObject('w', $move), $example);
    }

    public function testMoveh4()
    {
        $move = 'h3';
        $example = (object) [
            'pgn' => 'h3',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PAWN,
            'color' => 'w',
            'identity' => Symbol::PIECE_PAWN,
            'position' => (object) [
                'current' => 'h',
                'next' => 'h3'
            ]
        ];
        $this->assertEquals(Converter::toObject('w', $move), $example);
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
            'color' => 'w',
            'identity' => 'K',
            'position' => Castling::info('w')->{Symbol::PIECE_KING}->{Symbol::CASTLING_SHORT}->position
        ];
        $this->assertEquals(Converter::toObject('w', $move), $example);
    }

    public function testMoveLongCastling()
    {
        $move = 'O-O-O';
        $example = (object) [
            'pgn' => 'O-O-O',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::KING_CASTLING_LONG,
            'color' => 'w',
            'identity' => 'K',
            'position' => Castling::info('w')->{Symbol::PIECE_KING}->{Symbol::CASTLING_LONG}->position
        ];
        $this->assertEquals(Converter::toObject('w', $move), $example);
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
            'color' => 'b',
            'identity' => Symbol::PIECE_PAWN,
            'position' => (object) [
                'current' => 'f',
                'next' => 'g5'
            ]
        ];
        $this->assertEquals(Converter::toObject('b', $move), $example);
    }

    public function testMoveCaptureNxe4()
    {
        $move = 'Nxe4';
        $example = (object) [
            'pgn' => 'Nxe4',
            'isCapture' => true,
            'isCheck' => false,
            'type' => Move::KNIGHT_CAPTURES,
            'color' => 'b',
            'identity' => Symbol::PIECE_KNIGHT,
            'position' => (object) [
                'current' => null,
                'next' => 'e4'
            ]
        ];
        $this->assertEquals(Converter::toObject('b', $move), $example);
    }

    public function testMoveCaptureQ7xg7()
    {
        $move = 'Q7xg7';
        $example = (object) [
            'pgn' => 'Q7xg7',
            'isCapture' => true,
            'isCheck' => false,
            'type' => Move::PIECE_CAPTURES,
            'color' => 'b',
            'identity' => Symbol::PIECE_QUEEN,
            'position' => (object) [
                'current' => '7',
                'next' => 'g7'
            ]
        ];
        $this->assertEquals(Converter::toObject('b', $move), $example);
    }
}
