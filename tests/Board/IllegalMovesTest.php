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

class IllegalMovesTest extends \PHPUnit_Framework_TestCase
{
    public function testTurnNormal()
    {
        $board = new Board;
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e4')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_BLACK);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'e5')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
    }

    public function testTurnWithMistakes()
    {
        $board = new Board;
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'e4')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'O-O')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'O-O-O')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e4')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_BLACK);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e5')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_BLACK);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Nf3')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_BLACK);
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'e5')));
        $this->assertEquals($board->getStatus()->turn, Symbol::COLOR_WHITE);
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'Nc6')));
    }

    public function testPlayQg5()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject('b', 'Qg5')));
    }

    public function testPlayRa6()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'Ra6')));
    }

    public function testPlayRxa6()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject('b', 'Rxa6')));
    }

    public function testPlayBxe5()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'Bxe5')));
    }

    public function testPlayexd4()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'exd4')));
    }

    public function testPlayNxd2()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'Nxd2')));
    }

    public function testPlayNxc3()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'Nxc3')));
    }

    public function testPlayShortCastling()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'O-O')));
    }

    public function testPlayLongCastling()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'O-O-O')));
    }

    public function testCastlingShortInDefaultBoard()
    {
        $board = new Board;
        $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e4'));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'O-O')));
    }

    public function testKingForbiddenMove()
    {
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
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'Kf4')));
    }

    public function testCheckIsNotFixedKf4()
    {
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'a3'),
            new Pawn(Symbol::COLOR_WHITE, 'c3'),
            new Rook(Symbol::COLOR_WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'f3'), // in check!
            new Pawn(Symbol::COLOR_BLACK, 'a6'),
            new Pawn(Symbol::COLOR_BLACK, 'b5'),
            new Pawn(Symbol::COLOR_BLACK, 'c4'),
            new Knight(Symbol::COLOR_BLACK, 'd3'),
            new Rook(Symbol::COLOR_BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::COLOR_BLACK, 'g5'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'Kf4')));
    }

    public function testCheckIsNotFixedKf2()
    {
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'a3'),
            new Pawn(Symbol::COLOR_WHITE, 'c3'),
            new Rook(Symbol::COLOR_WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'f3'), // in check!
            new Pawn(Symbol::COLOR_BLACK, 'a6'),
            new Pawn(Symbol::COLOR_BLACK, 'b5'),
            new Pawn(Symbol::COLOR_BLACK, 'c4'),
            new Knight(Symbol::COLOR_BLACK, 'd3'),
            new Rook(Symbol::COLOR_BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::COLOR_BLACK, 'g5'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'Kf2')));
    }

    public function testCheckIsNotFixedRe7()
    {
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'a3'),
            new Pawn(Symbol::COLOR_WHITE, 'c3'),
            new Rook(Symbol::COLOR_WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'f3'), // in check!
            new Pawn(Symbol::COLOR_BLACK, 'a6'),
            new Pawn(Symbol::COLOR_BLACK, 'b5'),
            new Pawn(Symbol::COLOR_BLACK, 'c4'),
            new Knight(Symbol::COLOR_BLACK, 'd3'),
            new Rook(Symbol::COLOR_BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::COLOR_BLACK, 'g5'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'Re7')));
    }

    public function testCheckIsNotFixeda4()
    {
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'a3'),
            new Pawn(Symbol::COLOR_WHITE, 'c3'),
            new Rook(Symbol::COLOR_WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'f3'), // in check!
            new Pawn(Symbol::COLOR_BLACK, 'a6'),
            new Pawn(Symbol::COLOR_BLACK, 'b5'),
            new Pawn(Symbol::COLOR_BLACK, 'c4'),
            new Knight(Symbol::COLOR_BLACK, 'd3'),
            new Rook(Symbol::COLOR_BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::COLOR_BLACK, 'g5'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'a4')));
    }

    public function testKingCannotCaptureRookDefendedByKnight()
    {
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
            new Rook(Symbol::COLOR_BLACK, 'f2', RookType::CASTLING_SHORT), // rook defended by knight
            new King(Symbol::COLOR_BLACK, 'g5'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'Kxf2')));
    }

    public function testWhiteCastlesShortSicilianAfterNc6()
    {
        $board = new Board;
        $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e4'));
        $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'c5'));
        $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Nf3'));
        $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'Nc6'));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'O-O')));
    }

    public function testWhiteCastlesLongSicilianAfterNf6()
    {
        $board = new Board;
        $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e4'));
        $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'c5'));
        $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Nf3'));
        $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'Nc6'));
        $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Bb5'));
        $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'Nf6'));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'O-O-O')));
    }

    public function testCastlingThreateningf1()
    {
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'd4'),
            new Pawn(Symbol::COLOR_WHITE, 'e4'),
            new Pawn(Symbol::COLOR_WHITE, 'f2'),
            new Pawn(Symbol::COLOR_WHITE, 'g2'),
            new Pawn(Symbol::COLOR_WHITE, 'h2'),
            new Rook(Symbol::COLOR_WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'e1'),
            new Rook(Symbol::COLOR_WHITE, 'h1', RookType::CASTLING_SHORT),
            new Bishop(Symbol::COLOR_BLACK, 'a6'), // bishop threatening f1
            new King(Symbol::COLOR_BLACK, 'e8'),
            new Bishop(Symbol::COLOR_BLACK, 'f8'),
            new Knight(Symbol::COLOR_BLACK, 'g8'),
            new Rook(Symbol::COLOR_BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::COLOR_BLACK, 'f7'),
            new Pawn(Symbol::COLOR_BLACK, 'g7'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'O-O')));
    }

    public function testCastlingThreateningf1Andg1()
    {
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'd5'),
            new Pawn(Symbol::COLOR_WHITE, 'e4'),
            new Pawn(Symbol::COLOR_WHITE, 'f3'),
            new Pawn(Symbol::COLOR_WHITE, 'g2'),
            new Pawn(Symbol::COLOR_WHITE, 'h2'),
            new Rook(Symbol::COLOR_WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'e1'),
            new Rook(Symbol::COLOR_WHITE, 'h1', RookType::CASTLING_SHORT),
            new Bishop(Symbol::COLOR_BLACK, 'a6'), // bishop threatening f1
            new King(Symbol::COLOR_BLACK, 'e8'),
            new Bishop(Symbol::COLOR_BLACK, 'c5'), // bishop threatening g1
            new Knight(Symbol::COLOR_BLACK, 'g8'),
            new Rook(Symbol::COLOR_BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::COLOR_BLACK, 'f7'),
            new Pawn(Symbol::COLOR_BLACK, 'g7'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'O-O')));
    }

    public function testCastlingThreateningg1()
    {
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'd5'),
            new Pawn(Symbol::COLOR_WHITE, 'e4'),
            new Pawn(Symbol::COLOR_WHITE, 'f3'),
            new Pawn(Symbol::COLOR_WHITE, 'g2'),
            new Pawn(Symbol::COLOR_WHITE, 'h2'),
            new Rook(Symbol::COLOR_WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'e1'),
            new Rook(Symbol::COLOR_WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::COLOR_BLACK, 'e8'),
            new Bishop(Symbol::COLOR_BLACK, 'c5'), // bishop threatening g1
            new Knight(Symbol::COLOR_BLACK, 'g8'),
            new Rook(Symbol::COLOR_BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::COLOR_BLACK, 'f7'),
            new Pawn(Symbol::COLOR_BLACK, 'g7'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'O-O')));
    }

    public function testCastlingThreateningc1()
    {
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'd5'),
            new Pawn(Symbol::COLOR_WHITE, 'e4'),
            new Pawn(Symbol::COLOR_WHITE, 'f3'),
            new Pawn(Symbol::COLOR_WHITE, 'g2'),
            new Pawn(Symbol::COLOR_WHITE, 'h2'),
            new Rook(Symbol::COLOR_WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'e1'),
            new Rook(Symbol::COLOR_WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::COLOR_BLACK, 'e8'),
            new Bishop(Symbol::COLOR_BLACK, 'f4'), // bishop threatening c1
            new Knight(Symbol::COLOR_BLACK, 'g8'),
            new Rook(Symbol::COLOR_BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::COLOR_BLACK, 'f7'),
            new Pawn(Symbol::COLOR_BLACK, 'g7'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'O-O-O')));
    }

    public function testCastlingThreateningd1Andf1()
    {
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'd5'),
            new Pawn(Symbol::COLOR_WHITE, 'e4'),
            new Pawn(Symbol::COLOR_WHITE, 'f3'),
            new Pawn(Symbol::COLOR_WHITE, 'g2'),
            new Pawn(Symbol::COLOR_WHITE, 'h2'),
            new Rook(Symbol::COLOR_WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'e1'),
            new Rook(Symbol::COLOR_WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::COLOR_BLACK, 'e8'),
            new Bishop(Symbol::COLOR_BLACK, 'f8'),
            new Knight(Symbol::COLOR_BLACK, 'e3'), // knight threatening d1 and f1
            new Rook(Symbol::COLOR_BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::COLOR_BLACK, 'f7'),
            new Pawn(Symbol::COLOR_BLACK, 'g7'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'O-O')));
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'O-O-O')));
    }

    public function testCastlingThreateningb1Andf1()
    {
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'd5'),
            new Pawn(Symbol::COLOR_WHITE, 'e4'),
            new Pawn(Symbol::COLOR_WHITE, 'f3'),
            new Pawn(Symbol::COLOR_WHITE, 'g2'),
            new Pawn(Symbol::COLOR_WHITE, 'h2'),
            new Rook(Symbol::COLOR_WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'e1'),
            new Rook(Symbol::COLOR_WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::COLOR_BLACK, 'e8'),
            new Bishop(Symbol::COLOR_BLACK, 'f8'),
            new Knight(Symbol::COLOR_BLACK, 'd2'), // knight threatening b1 and f1
            new Rook(Symbol::COLOR_BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::COLOR_BLACK, 'f7'),
            new Pawn(Symbol::COLOR_BLACK, 'g7'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'O-O')));
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'O-O-O')));
    }

    public function testCastlingThreateningb1Andd1()
    {
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'd5'),
            new Pawn(Symbol::COLOR_WHITE, 'e4'),
            new Pawn(Symbol::COLOR_WHITE, 'f3'),
            new Pawn(Symbol::COLOR_WHITE, 'g2'),
            new Pawn(Symbol::COLOR_WHITE, 'h2'),
            new Rook(Symbol::COLOR_WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'e1'),
            new Rook(Symbol::COLOR_WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::COLOR_BLACK, 'e8'),
            new Bishop(Symbol::COLOR_BLACK, 'f8'),
            new Knight(Symbol::COLOR_BLACK, 'c3'), // knight threatening b1 and d1
            new Rook(Symbol::COLOR_BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::COLOR_BLACK, 'f7'),
            new Pawn(Symbol::COLOR_BLACK, 'g7'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'O-O-O')));
    }

    public function testForbidCastlingAfterKf1()
    {
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'd5'),
            new Pawn(Symbol::COLOR_WHITE, 'e4'),
            new Pawn(Symbol::COLOR_WHITE, 'f3'),
            new Pawn(Symbol::COLOR_WHITE, 'g2'),
            new Pawn(Symbol::COLOR_WHITE, 'h2'),
            new Rook(Symbol::COLOR_WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'e1'),
            new Rook(Symbol::COLOR_WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::COLOR_BLACK, 'e8'),
            new Bishop(Symbol::COLOR_BLACK, 'f8'),
            new Knight(Symbol::COLOR_BLACK, 'g8'),
            new Rook(Symbol::COLOR_BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::COLOR_BLACK, 'f7'),
            new Pawn(Symbol::COLOR_BLACK, 'g7'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(Converter::toObject('w', 'Kf1')));
        $this->assertEquals(true, $board->play(Converter::toObject('b', 'Nf6')));
        $this->assertEquals(true, $board->play(Converter::toObject('w', 'Ke1')));
        $this->assertEquals(true, $board->play(Converter::toObject('b', 'Nd7')));
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'O-O')));
    }

    public function testForbidCastlingAfterRg1()
    {
        $pieces = [
            new Pawn(Symbol::COLOR_WHITE, 'a2'),
            new Pawn(Symbol::COLOR_WHITE, 'd5'),
            new Pawn(Symbol::COLOR_WHITE, 'e4'),
            new Pawn(Symbol::COLOR_WHITE, 'f3'),
            new Pawn(Symbol::COLOR_WHITE, 'g2'),
            new Pawn(Symbol::COLOR_WHITE, 'h2'),
            new Rook(Symbol::COLOR_WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::COLOR_WHITE, 'e1'),
            new Rook(Symbol::COLOR_WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::COLOR_BLACK, 'e8'),
            new Bishop(Symbol::COLOR_BLACK, 'f8'),
            new Knight(Symbol::COLOR_BLACK, 'g8'),
            new Rook(Symbol::COLOR_BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::COLOR_BLACK, 'f7'),
            new Pawn(Symbol::COLOR_BLACK, 'g7'),
            new Pawn(Symbol::COLOR_BLACK, 'h7')
        ];
        $board = new Board($pieces);
        $this->assertEquals(true, $board->play(Converter::toObject('w', 'Rg1')));
        $this->assertEquals(true, $board->play(Converter::toObject('b', 'Nf6')));
        $this->assertEquals(true, $board->play(Converter::toObject('w', 'Rh1')));
        $this->assertEquals(true, $board->play(Converter::toObject('b', 'Nd7')));
        $this->assertEquals(false, $board->play(Converter::toObject('w', 'O-O')));
    }

    public function testForbidCastlingRuyLopez()
    {
        $board = new Board;
        $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e4'));
        $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'e5'));
        $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Nf3'));
        $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'Nc6'));
        $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Bb5'));
        $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'Nf6'));
        $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ke2'));
        $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'Bb4'));
        $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ke1'));
        $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'Ke7'));
        $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Nc3'));
        $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'Ke8'));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'O-O')));
    }

    public function testGame01()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'O-O-O')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e5')));

        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'e5')));

        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Converter::toObject(Symbol::COLOR_BLACK, 'Nc6')));

        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra2')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra3')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra4')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra5')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra6')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra7')));
        $this->assertEquals(false, $board->play(Converter::toObject(Symbol::COLOR_WHITE, 'Ra8')));

        // ...
    }
}
