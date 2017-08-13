<?php
namespace PGNChess\Tests\Board;

use PGNChess\Board;
use PGNChess\PGN\Converter;
use PGNChess\PGN\Symbol;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;
use PGNChess\Type\RookType;

class InvalidMovesTest extends \PHPUnit_Framework_TestCase
{
    public function testNumericValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 9)));
    }

    public function testFoo()
    {
        $this->expectException(\InvalidArgumentException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'foo')));
    }

    public function testBar()
    {
        $this->expectException(\InvalidArgumentException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'bar')));
    }

    public function test_e9()
    {
        $this->expectException(\InvalidArgumentException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e9')));
    }

    public function test_e10()
    {
        $this->expectException(\InvalidArgumentException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e10')));
    }

    public function testNw3()
    {
        $this->expectException(\InvalidArgumentException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Nw3')));
    }

    public function testPlayRaInDefaultBoard()
    {
        $board = new Board;
        $squares = [];
        $letter = 'a';
        for($i=0; $i<8; $i++)
        {
            for($j=1; $j<=8; $j++)
            {
                $this->assertEquals(false, $board->play(Converter::toObject('w', 'Ra' . chr((ord('a') + $i)) . $j)));
            }
        }
    }

    public function testThrowsExceptionPieceDoesNotExistOnTheBoard()
    {
        $this->expectException(\InvalidArgumentException::class);
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'a3'),
            new Pawn(Symbol::COLOR_WHITE, 'c3'),
            new Rook(Symbol::COLOR_WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'g3'),
            new Pawn(Symbol::COLOR_BLACK, 'a6'),
            new Pawn(Symbol::COLOR_BLACK, 'b5'),
            new Pawn(Symbol::COLOR_BLACK, 'c4'),
            new Knight(Symbol::COLOR_BLACK, 'd3'),
            new Rook(Symbol::COLOR_BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::COLOR_BLACK, 'g5'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $board->play(Converter::toObject('w', 'f4'));
    }
}
