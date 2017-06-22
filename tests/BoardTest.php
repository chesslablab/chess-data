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

    public function testGetPieceToMoveQg3()
    {
        $board = new Board;
        $piece = $board->getPieceToMove(PGN::arrayizeMove('w', 'Qg3'));
        $this->assertInstanceOf(Queen::class, $piece);
        $this->assertEquals($piece->getPosition()->current, 'd1');
    }

    public function testGetPieceToMoveRh3()
    {
        $board = new Board;
        $piece = $board->getPieceToMove(PGN::arrayizeMove('w', 'Rh3'));
        $this->assertInstanceOf(Rook::class, $piece);
        $this->assertEquals($piece->getPosition()->current, 'a1');
    }

    public function testIsLegalMoveRa6InDefaultBoard()
    {
        $board = new Board;
        $move = PGN::arrayizeMove('w', 'Ra6');
        $piece = $board->getPieceToMove($move);
        $this->assertEquals(false, $board->isLegalMove($piece, $move));
    }

    public function testIsLegalMoveRa6InCustomBoard()
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
        $move = PGN::arrayizeMove('w', 'Ra6');
        $piece = $board->getPieceToMove($move);
        $this->assertEquals(true, $board->isLegalMove($piece, $move));
    }

    public function testIsLegalMoveCaptureRxa6InDefaultBoard()
    {
        $board = new Board;
        $move = PGN::arrayizeMove('w', 'Rxa6');
        $piece = $board->getPieceToMove($move);
        $this->assertEquals(false, $board->isLegalMove($piece, $move));
    }

    public function testIsLegalMoveCaptureBxe5InDefaultBoard()
    {
        $board = new Board;
        $move = PGN::arrayizeMove('w', 'Bxe5');
        $piece = $board->getPieceToMove($move);
        $this->assertEquals(false, $board->isLegalMove($piece, $move));
    }

    public function testIsLegalMoveCaptureexd4InDefaultBoard()
    {
        $board = new Board;
        $move = PGN::arrayizeMove('w', 'exd4');
        $piece = $board->getPieceToMove($move);
        $this->assertEquals(false, $board->isLegalMove($piece, $move));
    }

    public function testIsLegalMoveCaptureRxa6InCustomBoard()
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
        $move = PGN::arrayizeMove('w', 'Rxa6');
        $piece = $board->getPieceToMove($move);
        $this->assertEquals(true, $board->isLegalMove($piece, $move));
    }

    public function testIsLegalMoveh6InCustomBoard()
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
        $move = PGN::arrayizeMove('b', 'h6');
        $piece = $board->getPieceToMove($move);
        $this->assertEquals(true, $board->isLegalMove($piece, $move));
    }

    public function testIsLegalCaptureh6InCustomBoard()
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
        $move = PGN::arrayizeMove('b', 'hxg6');
        $piece = $board->getPieceToMove($move);
        $this->assertEquals(true, $board->isLegalMove($piece, $move));
    }
}
