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

    public function testPlayNf6()
    {
        $board = new Board;
        $this->assertEquals(true, $board->play(PGN::objectizeMove('b', 'Nf6')));
    }

    public function testPlayNxd2()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'Nxd2')));
    }

    /**
     * TODO look at this test: make sure that Xxyn is equivalent to Xyn in all cases
     * whenever the destination square is empty.
     */
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

    public function testKingForbiddenMove()
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
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'Kf4')));
    }

    public function testCheckIsFixedKe4()
    {
        $pieces = [
            new Pawn(PGN::COLOR_WHITE, 'a2'),
            new Pawn(PGN::COLOR_WHITE, 'a3'),
            new Pawn(PGN::COLOR_WHITE, 'c3'),
            new Rook(PGN::COLOR_WHITE, 'e6'),
            new King(PGN::COLOR_WHITE, 'f3'), // in check!
            new Pawn(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'b5'),
            new Pawn(PGN::COLOR_BLACK, 'c4'),
            new Knight(PGN::COLOR_BLACK, 'd3'),
            new Rook(PGN::COLOR_BLACK, 'f5'),
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(PGN::objectizeMove('w', 'Ke4')));
    }

    public function testCheckIsNotFixedKf4()
    {
        $pieces = [
            new Pawn(PGN::COLOR_WHITE, 'a2'),
            new Pawn(PGN::COLOR_WHITE, 'a3'),
            new Pawn(PGN::COLOR_WHITE, 'c3'),
            new Rook(PGN::COLOR_WHITE, 'e6'),
            new King(PGN::COLOR_WHITE, 'f3'), // in check!
            new Pawn(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'b5'),
            new Pawn(PGN::COLOR_BLACK, 'c4'),
            new Knight(PGN::COLOR_BLACK, 'd3'),
            new Rook(PGN::COLOR_BLACK, 'f5'),
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'Kf4')));
    }

    public function testCheckIsNotFixedKg4()
    {
        $pieces = [
            new Pawn(PGN::COLOR_WHITE, 'a2'),
            new Pawn(PGN::COLOR_WHITE, 'a3'),
            new Pawn(PGN::COLOR_WHITE, 'c3'),
            new Rook(PGN::COLOR_WHITE, 'e6'),
            new King(PGN::COLOR_WHITE, 'f3'), // in check!
            new Pawn(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'b5'),
            new Pawn(PGN::COLOR_BLACK, 'c4'),
            new Knight(PGN::COLOR_BLACK, 'd3'),
            new Rook(PGN::COLOR_BLACK, 'f5'),
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'Kg4')));
    }

    public function testCheckIsFixedKg3()
    {
        $pieces = [
            new Pawn(PGN::COLOR_WHITE, 'a2'),
            new Pawn(PGN::COLOR_WHITE, 'a3'),
            new Pawn(PGN::COLOR_WHITE, 'c3'),
            new Rook(PGN::COLOR_WHITE, 'e6'),
            new King(PGN::COLOR_WHITE, 'f3'), // in check!
            new Pawn(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'b5'),
            new Pawn(PGN::COLOR_BLACK, 'c4'),
            new Knight(PGN::COLOR_BLACK, 'd3'),
            new Rook(PGN::COLOR_BLACK, 'f5'),
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(PGN::objectizeMove('w', 'Kg3')));
    }

    public function testCheckIsFixedKg2()
    {
        $pieces = [
            new Pawn(PGN::COLOR_WHITE, 'a2'),
            new Pawn(PGN::COLOR_WHITE, 'a3'),
            new Pawn(PGN::COLOR_WHITE, 'c3'),
            new Rook(PGN::COLOR_WHITE, 'e6'),
            new King(PGN::COLOR_WHITE, 'f3'), // in check!
            new Pawn(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'b5'),
            new Pawn(PGN::COLOR_BLACK, 'c4'),
            new Knight(PGN::COLOR_BLACK, 'd3'),
            new Rook(PGN::COLOR_BLACK, 'f5'),
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(PGN::objectizeMove('w', 'Kg2')));
    }

    public function testCheckIsNotFixedKf2()
    {
        $pieces = [
            new Pawn(PGN::COLOR_WHITE, 'a2'),
            new Pawn(PGN::COLOR_WHITE, 'a3'),
            new Pawn(PGN::COLOR_WHITE, 'c3'),
            new Rook(PGN::COLOR_WHITE, 'e6'),
            new King(PGN::COLOR_WHITE, 'f3'), // in check!
            new Pawn(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'b5'),
            new Pawn(PGN::COLOR_BLACK, 'c4'),
            new Knight(PGN::COLOR_BLACK, 'd3'),
            new Rook(PGN::COLOR_BLACK, 'f5'),
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'Kf2')));
    }

    public function testCheckIsFixedKe2()
    {
        $pieces = [
            new Pawn(PGN::COLOR_WHITE, 'a2'),
            new Pawn(PGN::COLOR_WHITE, 'a3'),
            new Pawn(PGN::COLOR_WHITE, 'c3'),
            new Rook(PGN::COLOR_WHITE, 'e6'),
            new King(PGN::COLOR_WHITE, 'f3'), // in check!
            new Pawn(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'b5'),
            new Pawn(PGN::COLOR_BLACK, 'c4'),
            new Knight(PGN::COLOR_BLACK, 'd3'),
            new Rook(PGN::COLOR_BLACK, 'f5'),
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(PGN::objectizeMove('w', 'Ke2')));
    }

    public function testCheckIsFixedKe3()
    {
        $pieces = [
            new Pawn(PGN::COLOR_WHITE, 'a2'),
            new Pawn(PGN::COLOR_WHITE, 'a3'),
            new Pawn(PGN::COLOR_WHITE, 'c3'),
            new Rook(PGN::COLOR_WHITE, 'e6'),
            new King(PGN::COLOR_WHITE, 'f3'), // in check!
            new Pawn(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'b5'),
            new Pawn(PGN::COLOR_BLACK, 'c4'),
            new Knight(PGN::COLOR_BLACK, 'd3'),
            new Rook(PGN::COLOR_BLACK, 'f5'),
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(PGN::objectizeMove('w', 'Ke3')));
    }

    public function testCheckIsNotFixedRe7()
    {
        $pieces = [
            new Pawn(PGN::COLOR_WHITE, 'a2'),
            new Pawn(PGN::COLOR_WHITE, 'a3'),
            new Pawn(PGN::COLOR_WHITE, 'c3'),
            new Rook(PGN::COLOR_WHITE, 'e6'),
            new King(PGN::COLOR_WHITE, 'f3'), // in check!
            new Pawn(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'b5'),
            new Pawn(PGN::COLOR_BLACK, 'c4'),
            new Knight(PGN::COLOR_BLACK, 'd3'),
            new Rook(PGN::COLOR_BLACK, 'f5'),
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'Re7')));
    }

    public function testCheckIsNotFixeda4()
    {
        $pieces = [
            new Pawn(PGN::COLOR_WHITE, 'a2'),
            new Pawn(PGN::COLOR_WHITE, 'a3'),
            new Pawn(PGN::COLOR_WHITE, 'c3'),
            new Rook(PGN::COLOR_WHITE, 'e6'),
            new King(PGN::COLOR_WHITE, 'f3'), // in check!
            new Pawn(PGN::COLOR_BLACK, 'a6'),
            new Pawn(PGN::COLOR_BLACK, 'b5'),
            new Pawn(PGN::COLOR_BLACK, 'c4'),
            new Knight(PGN::COLOR_BLACK, 'd3'),
            new Rook(PGN::COLOR_BLACK, 'f5'),
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'a4')));
    }

    public function testThrowsExceptionPieceDoesNotExistOnTheBoard()
    {
        $this->expectException(\InvalidArgumentException::class);
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
        $board->play(PGN::objectizeMove('w', 'f4'));
    }

    public function testKingLegalMove()
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
        $this->assertEquals(true, $board->play(PGN::objectizeMove('w', 'Kg2')));
    }

    public function testKingLegalCapture()
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
            new Rook(PGN::COLOR_BLACK, 'h2'),
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(PGN::objectizeMove('w', 'Kxh2')));
    }

    public function testKingCannotCaptureRookDefendedByKnight()
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
            new Rook(PGN::COLOR_BLACK, 'f2'), // rook defended by knight
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(PGN::objectizeMove('w', 'Kxf2')));
    }

    public function testKingCannCaptureRookNotDefended()
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
            new Rook(PGN::COLOR_BLACK, 'f3'), // rook not defended
            new King(PGN::COLOR_BLACK, 'g5'),
            new Pawn(PGN::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(PGN::objectizeMove('w', 'Kxf3')));
    }

    public function testPlayGameAndCheckStatus()
    {
        $game = [
            'e4 e5',
            'f4 exf4',
            'd4 Nf6',
            'Nc3 Bb4',
            'Bxf4 Bxc3+'
        ];
        $board = new Board;
        foreach ($game as $entry)
        {
            $moves = explode(' ', $entry);
            $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, $moves[0]));
            $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, $moves[1]));
        }
        $example = (object) [
            'w' => [
                'a3',
                'a6',
                'b1',
                'b3',
                'b5',
                'c1',
                'c4',
                'c5',
                'd2',
                'd3',
                'd5',
                'd6',
                'e2',
                'e3',
                'e5',
                'f2',
                'f3',
                'f5',
                'g3',
                'g4',
                'g5',
                'h3',
                'h5',
                'h6'
            ],
            'b' => [
                'a5',
                'a6',
                'b4',
                'b6',
                'c6',
                'd2',
                'd5',
                'd6',
                'e6',
                'e7',
                'f8',
                'g4',
                'g6',
                'g8',
                'h5',
                'h6'
            ]
        ];
        $this->assertEquals($example, $board->getStatus()->space);
    }

    public function testCastlingShortInDefaultBoard()
    {
        $board = new Board;
        $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'e4'));
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'O-O')));
    }

    public function testWhiteCastlesShortSicilianAfterNc6()
    {
        $board = new Board;
        $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'e4'));
        $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'c5'));
        $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Nf3'));
        $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'Nc6'));
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'O-O')));
    }

    public function testWhiteCastlesShortSicilianAfterNf6()
    {
        $board = new Board;
        $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'e4'));
        $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'c5'));
        $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Nf3'));
        $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'Nc6'));
        $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Bb5'));
        $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'Nf6'));
        $this->assertEquals(true, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'O-O')));
    }

    public function testWhiteCastlesLongSicilianAfterNf6()
    {
        $board = new Board;
        $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'e4'));
        $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'c5'));
        $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Nf3'));
        $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'Nc6'));
        $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Bb5'));
        $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'Nf6'));
        $this->assertEquals(false, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'O-O-O')));
    }

    public function testWhiteCastlesShortSicilianAfterNf6BoardStatus()
    {
        $board = new Board;
        $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'e4'));
        $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'c5'));
        $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Nf3'));
        $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'Nc6'));
        $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'Bb5'));
        $board->play(PGN::objectizeMove(PGN::COLOR_BLACK, 'Nf6'));
        $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'O-O'));

        print_r($board->getStatus()->castling); exit;

        $status = $board->getStatus()->castling->w->K->isCastled;

        // echo 'Foo: ' . (int)$status;

        // $this->assertEquals(true, $board->play(PGN::objectizeMove(PGN::COLOR_WHITE, 'O-O')));
    }

}
