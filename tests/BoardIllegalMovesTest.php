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
use PGNChess\Piece\Type\RookType;

class IllegalMovesTest extends \PHPUnit_Framework_TestCase
{
    public function testTurnNormal()
    {
        $board = new Board;
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals($board->getTurn(), Symbol::BLACK);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::BLACK, 'e5')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
    }

    public function testTurnWithMistakes()
    {
        $board = new Board;
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::BLACK, 'e4')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O-O')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals($board->getTurn(), Symbol::BLACK);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'e5')));
        $this->assertEquals($board->getTurn(), Symbol::BLACK);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Nf3')));
        $this->assertEquals($board->getTurn(), Symbol::BLACK);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::BLACK, 'e5')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::BLACK, 'Nc6')));
    }

    public function testPlayQg5()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::BLACK, 'Qg5')));
    }

    public function testPlayRa6()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Ra6')));
    }

    public function testPlayRxa6()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::BLACK, 'Rxa6')));
    }

    public function testPlayBxe5()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Bxe5')));
    }

    public function testPlayexd4()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'exd4')));
    }

    public function testPlayNxd2()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Nxd2')));
    }

    public function testPlayNxc3()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Nxc3')));
    }

    public function testPlayShortCastling()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testPlayLongCastling()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O-O')));
    }

    public function testCastlingShortInDefaultBoard()
    {
        $board = new Board;
        $board->play(Converter::toObject(Symbol::WHITE, 'e4'));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::BLACK, 'O-O')));
    }

    public function testKingForbiddenMove()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'g3'),
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Kf4')));
    }

    public function testCheckIsNotFixedKf4()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Kf4')));
    }

    public function testCheckIsNotFixedKf2()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Kf2')));
    }

    public function testCheckIsNotFixedRe7()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Re7')));
    }

    public function testCheckIsNotFixeda4()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'a4')));
    }

    public function testKingCannotCaptureRookDefendedByKnight()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'g3'),
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f2', RookType::CASTLING_SHORT), // rook defended by knight
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Kxf2')));
    }

    public function testWhiteCastlesShortSicilianAfterNc6()
    {
        $board = new Board;
        $board->play(Converter::toObject(Symbol::WHITE, 'e4'));
        $board->play(Converter::toObject(Symbol::BLACK, 'c5'));
        $board->play(Converter::toObject(Symbol::WHITE, 'Nf3'));
        $board->play(Converter::toObject(Symbol::BLACK, 'Nc6'));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testWhiteCastlesLongSicilianAfterNf6()
    {
        $board = new Board;
        $board->play(Converter::toObject(Symbol::WHITE, 'e4'));
        $board->play(Converter::toObject(Symbol::BLACK, 'c5'));
        $board->play(Converter::toObject(Symbol::WHITE, 'Nf3'));
        $board->play(Converter::toObject(Symbol::BLACK, 'Nc6'));
        $board->play(Converter::toObject(Symbol::WHITE, 'Bb5'));
        $board->play(Converter::toObject(Symbol::BLACK, 'Nf6'));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O-O')));
    }

    public function testCastlingThreateningf1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd4'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f2'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new Bishop(Symbol::BLACK, 'a6'), // bishop threatening f1
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'g8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testCastlingThreateningf1Andg1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new Bishop(Symbol::BLACK, 'a6'), // bishop threatening f1
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'c5'), // bishop threatening g1
            new Knight(Symbol::BLACK, 'g8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testCastlingThreateningg1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'c5'), // bishop threatening g1
            new Knight(Symbol::BLACK, 'g8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testCastlingThreateningc1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f4'), // bishop threatening c1
            new Knight(Symbol::BLACK, 'g8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O-O')));
    }

    public function testCastlingThreateningd1Andf1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'e3'), // knight threatening d1 and f1
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O-O')));
    }

    public function testCastlingThreateningb1Andf1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'd2'), // knight threatening b1 and f1
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O-O')));
    }

    public function testCastlingThreateningb1Andd1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'c3'), // knight threatening b1 and d1
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O-O')));
    }

    public function testForbidCastlingAfterKf1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'g8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::WHITE, 'Kf1')));
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::BLACK, 'Nf6')));
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::WHITE, 'Ke1')));
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::BLACK, 'Nd7')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testForbidCastlingAfterRg1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'g8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::WHITE, 'Rg1')));
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::BLACK, 'Nf6')));
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::WHITE, 'Rh1')));
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::BLACK, 'Nd7')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testForbidCastlingRuyLopez()
    {
        $board = new Board;
        $board->play(Converter::toObject(Symbol::WHITE, 'e4'));
        $board->play(Converter::toObject(Symbol::BLACK, 'e5'));
        $board->play(Converter::toObject(Symbol::WHITE, 'Nf3'));
        $board->play(Converter::toObject(Symbol::BLACK, 'Nc6'));
        $board->play(Converter::toObject(Symbol::WHITE, 'Bb5'));
        $board->play(Converter::toObject(Symbol::BLACK, 'Nf6'));
        $board->play(Converter::toObject(Symbol::WHITE, 'Ke2'));
        $board->play(Converter::toObject(Symbol::BLACK, 'Bb4'));
        $board->play(Converter::toObject(Symbol::WHITE, 'Ke1'));
        $board->play(Converter::toObject(Symbol::BLACK, 'Ke7'));
        $board->play(Converter::toObject(Symbol::WHITE, 'Nc3'));
        $board->play(Converter::toObject(Symbol::BLACK, 'Ke8'));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::BLACK, 'O-O')));
    }

    public function testGame01()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'O-O-O')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'e5')));

        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::BLACK, 'e5')));

        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::BLACK, 'Nc6')));

        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Ra2')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Ra3')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Ra4')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Ra5')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Ra6')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Ra7')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::WHITE, 'Ra8')));

        // ...
    }
}
