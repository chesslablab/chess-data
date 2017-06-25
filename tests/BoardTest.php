<?php
namespace PGNChess\Tests;

use PGNChess\PGN;
use PGNChess\Board;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;

class BoardTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateDefaultBoard()
    {
        $board = new Board;
        $this->assertEquals(count($board), 32);
        $this->assertEquals(count($board->getStatus()->squares->used->w), 16);
        $this->assertEquals(count($board->getStatus()->squares->used->b), 16);
    }

    public function testInstantiateCustomBoard()
    {
        $pieces = [
            new Bishop(PGN::COLOR_WHITE, 'c1'),
            new Queen(PGN::COLOR_WHITE, 'd1'),
            new King(PGN::COLOR_WHITE, 'e1'),
            new Pawn(PGN::COLOR_WHITE, 'e2'),
            new King(PGN::COLOR_BLACK, 'e8'),
            new Bishop(PGN::COLOR_BLACK, 'f8'),
            new Knight(PGN::COLOR_BLACK, 'g8')
        ];
        $board = new Board($pieces);
        $this->assertEquals(count($board), 7);
        $this->assertEquals(count($board->getStatus()->squares->used->w), 4);
        $this->assertEquals(count($board->getStatus()->squares->used->b), 3);
    }

    public function testPlayQg3()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(PGN::objectizeMove('b', 'Qg5')));
    }

    public function testSquaresControlledByWhiteInDefaultBoard()
    {
        $pieces = [
            new Pawn(PGN::COLOR_WHITE, 'a2'),
            new Pawn(PGN::COLOR_WHITE, 'a3'),
            new Pawn(PGN::COLOR_WHITE, 'c3'),
            new Rook(PGN::COLOR_WHITE, 'e6'),
            new King(PGN::COLOR_WHITE, 'g3'),
            new Pawn(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'b5'),
            new Pawn(PGN::COLOR_BLACK, 'c4'),
            new Knight(PGN::COLOR_BLACK, 'd3'),
            new Rook(PGN::COLOR_BLACK, 'f5'),
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        // print_r($board->getStatus()->squares->controlled->w); exit;
        $example = [
            'a3',
            'b3',
            'c3',
            'd3',
            'e3',
            'f3',
            'g3',
            'h3'
        ];
        $this->assertEquals($example, $board->getStatus()->squares->controlled->w);
    }

    public function testSquaresControlledByBlackInDefaultBoard()
    {
        $board = new Board;
        $example = [
            'a6',
            'b6',
            'c6',
            'd6',
            'e6',
            'f6',
            'g6',
            'h6'
        ];
        $this->assertEquals($example, $board->getStatus()->squares->controlled->b);
    }

    public function testSquaresControlledByWhiteInCustomBoard()
    {
        $board = new Board;
        $example = [
            'a3',
            'b3',
            'c3',
            'd3',
            'e3',
            'f3',
            'g3',
            'h3'
        ];
        $this->assertEquals($example, $board->getStatus()->squares->controlled->w);
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
                $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'Ra' . chr((ord('a') + $i)) . $j)));
            }
        }
    }

    public function testPlayRa6()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'Ra6')));
    }

    public function testPlayRa6InCustomBoard()
    {
        $pieces = [
            new Rook(PGN::COLOR_WHITE, 'a1'),
            // new Knight(PGN::COLOR_WHITE, 'b1'),
            // new Bishop(PGN::COLOR_WHITE, 'c1'),
            new Queen(PGN::COLOR_WHITE, 'd1'),
            new King(PGN::COLOR_WHITE, 'e1'),
            new Bishop(PGN::COLOR_WHITE, 'f1'),
            new Knight(PGN::COLOR_WHITE, 'g1'),
            new Rook(PGN::COLOR_WHITE, 'h1'),
            // new Pawn(PGN::COLOR_WHITE, 'a2'),
            new Pawn(PGN::COLOR_WHITE, 'b2'),
            new Pawn(PGN::COLOR_WHITE, 'c2'),
            new Pawn(PGN::COLOR_WHITE, 'd2'),
            new Pawn(PGN::COLOR_WHITE, 'e2'),
            new Pawn(PGN::COLOR_WHITE, 'f2'),
            new Pawn(PGN::COLOR_WHITE, 'g2'),
            new Pawn(PGN::COLOR_WHITE, 'h2'),
            new Rook(PGN::COLOR_BLACK, 'a8'),
            new Knight(PGN::COLOR_BLACK, 'b8'),
            new Bishop(PGN::COLOR_BLACK, 'c8'),
            new Queen(PGN::COLOR_BLACK, 'd8'),
            new King(PGN::COLOR_BLACK, 'e8'),
            new Bishop(PGN::COLOR_BLACK, 'f8'),
            new Knight(PGN::COLOR_BLACK, 'g8'),
            new Rook(PGN::COLOR_BLACK, 'h8'),
            new Pawn(PGN::COLOR_BLACK, 'a7'),
            new Pawn(PGN::COLOR_BLACK, 'b7'),
            new Pawn(PGN::COLOR_BLACK, 'c7'),
            new Pawn(PGN::COLOR_BLACK, 'd7'),
            new Pawn(PGN::COLOR_BLACK, 'e7'),
            new Pawn(PGN::COLOR_BLACK, 'f7'),
            new Pawn(PGN::COLOR_BLACK, 'g7'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(PGN::objectizeMove('w', 'Ra6')));
    }

    public function testPlayRxa6()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(PGN::objectizeMove('b', 'Rxa6')));
    }

    public function testPlayBxe5()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'Bxe5')));
    }

    public function testPlayexd4()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'exd4')));
    }

    public function testPlayRxa6InCustomBoard()
    {
        $pieces = [
            new Rook(PGN::COLOR_WHITE, 'a1'),
            new King(PGN::COLOR_WHITE, 'e1'),
            new King(PGN::COLOR_BLACK, 'e8'),
            new Bishop(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'g7'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(PGN::objectizeMove('w', 'Rxa6')));
    }

    public function testPlayh6InCustomBoard()
    {
        $pieces = [
            new Rook(PGN::COLOR_WHITE, 'a1'),
            new King(PGN::COLOR_WHITE, 'e1'),
            new King(PGN::COLOR_BLACK, 'e8'),
            new Bishop(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'g7'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(PGN::objectizeMove('b', 'h6')));
    }

    public function testPlayhxg6InCustomBoard()
    {
        $pieces = [
            new Rook(PGN::COLOR_WHITE, 'a1'),
            new King(PGN::COLOR_WHITE, 'e1'),
            new Pawn(PGN::COLOR_WHITE, 'g6'),
            new King(PGN::COLOR_BLACK, 'e8'),
            new Bishop(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'g7'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(PGN::objectizeMove('b', 'hxg6')));
    }

    public function testPlayNc3()
    {
        $board = new Board;
        $this->assertEquals(true, $board->play(PGN::objectizeMove('w', 'Nc3')));
    }

    public function testPlayNc6()
    {
        $board = new Board;
        $this->assertEquals(true, $board->play(PGN::objectizeMove('b', 'Nc6')));
    }

    public function testPlayNxd2()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'Nxd2')));
    }

    public function testPlayNxc3()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'Nxc3')));
    }

    public function testPlayNxc3InCustomBoard()
    {
        $pieces = [
            new Knight(PGN::COLOR_WHITE, 'b1'),
            new King(PGN::COLOR_WHITE, 'e1'),
            new Pawn(PGN::COLOR_WHITE, 'g6'),
            new King(PGN::COLOR_BLACK, 'e8'),
            new Bishop(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'c3'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(PGN::objectizeMove('w', 'Nxc3')));
    }

    public function testPlayShortCastling()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'O-O')));
    }

    public function testPlayLongCastling()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'O-O-O')));
    }

    public function testPlayShortCastlingInCustomBoard()
    {
        $pieces = [
            new Rook(PGN::COLOR_WHITE, 'a1'),
            new Knight(PGN::COLOR_WHITE, 'b1'),
            new Bishop(PGN::COLOR_WHITE, 'c1'),
            new Queen(PGN::COLOR_WHITE, 'd1'),
            new King(PGN::COLOR_WHITE, 'e1'),
            new Bishop(PGN::COLOR_WHITE, 'f1'),
            new Knight(PGN::COLOR_WHITE, 'g1'),
            new Rook(PGN::COLOR_WHITE, 'h1'),
            new Pawn(PGN::COLOR_WHITE, 'a2'),
            new Pawn(PGN::COLOR_WHITE, 'b2'),
            new Pawn(PGN::COLOR_WHITE, 'c2'),
            new Pawn(PGN::COLOR_WHITE, 'd2'),
            new Pawn(PGN::COLOR_WHITE, 'e2'),
            new Pawn(PGN::COLOR_WHITE, 'f2'),
            new Pawn(PGN::COLOR_WHITE, 'g2'),
            new Pawn(PGN::COLOR_WHITE, 'h2'),
            new Rook(PGN::COLOR_BLACK, 'a8'),
            new Knight(PGN::COLOR_BLACK, 'b8'),
            new Bishop(PGN::COLOR_BLACK, 'c8'),
            new Queen(PGN::COLOR_BLACK, 'd8'),
            new King(PGN::COLOR_BLACK, 'e8'),
            // new Bishop(PGN::COLOR_BLACK, 'f8'),
            // new Knight(PGN::COLOR_BLACK, 'g8'),
            new Rook(PGN::COLOR_BLACK, 'h8'),
            new Pawn(PGN::COLOR_BLACK, 'a7'),
            new Pawn(PGN::COLOR_BLACK, 'b7'),
            new Pawn(PGN::COLOR_BLACK, 'c7'),
            new Pawn(PGN::COLOR_BLACK, 'd7'),
            // new Pawn(PGN::COLOR_BLACK, 'e7'),
            new Pawn(PGN::COLOR_BLACK, 'f7'),
            new Pawn(PGN::COLOR_BLACK, 'g7'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(PGN::objectizeMove('b', 'O-O')));
    }

}
