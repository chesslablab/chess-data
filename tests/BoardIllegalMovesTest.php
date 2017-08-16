<?php
namespace PGNChess\Tests;

use PGNChess\Board;
use PGNChess\PGN\Convert;
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
    public function testNormalTurn()
    {
        $board = new Board;

        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals($board->getTurn(), Symbol::BLACK);
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'e5')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
    }

    public function testWrongTurn()
    {
        $board = new Board;

        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'e4')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals($board->getTurn(), Symbol::BLACK);
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'e5')));
        $this->assertEquals($board->getTurn(), Symbol::BLACK);
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Nf3')));
        $this->assertEquals($board->getTurn(), Symbol::BLACK);
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'e5')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'Nc6')));
    }

    public function test_Qg5()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'Qg5')));
    }

    public function test_Ra6()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra6')));
    }

    public function test_Rxa6()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'Rxa6')));
    }

    public function test_Bxe5()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Bxe5')));
    }

    public function test_exd4()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'exd4')));
    }

    public function test_Nxd2()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Nxd2')));
    }

    public function test_Nxc3()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Nxc3')));
    }

    public function testWhiteCastlesShort()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testLongCastling()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
    }

    public function testBlackCastlesShort()
    {
        $board = new Board;
        $board->play(Convert::toObject(Symbol::WHITE, 'e4'));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'O-O')));
    }

    public function test_Kf4()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Kf4')));
    }

    public function test_Kf4_InCheck()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Kf4')));
    }

    public function test_Kf2_InCheck()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Kf2')));
    }

    public function test_Re7_InCheck()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Re7')));
    }

    public function test_a4_InCheck()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'a4')));
    }

    public function test_Kxf2()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Kxf2')));
    }

    public function testWhiteShortCastlingAfter_Nc6()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'e4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'c5'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Nf3'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nc6'));

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testWhiteLongCastlingAfter_Nf6()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'e4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'c5'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Nf3'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nc6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Bb5'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nf6'));

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
    }

    public function testCastlingThreatening_f1()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testCastlingThreatening_f1_g1()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testCastlingThreatening_g1()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testCastlingThreatening_c1()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
    }

    public function testCastlingThreatening_d1_f1()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
    }

    public function testCastlingThreatening_b1_f1()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
    }

    public function testCastlingThreatening_b1_d1()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
    }

    public function testCastlingAfter_Kf1()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Kf1')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nf6')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Ke1')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nd7')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testCastlingAfter_Rg1()
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

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Rg1')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nf6')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Rh1')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nd7')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    public function testCastlingInRuyLopez()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'e4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'e5'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Nf3'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nc6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Bb5'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nf6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Ke2'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Bb4'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Ke1'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Ke7'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Nc3'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Ke8'));

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'O-O')));
    }

    public function testFalslyGame()
    {
        $board = new Board;

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'e5')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'e5')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nc6')));

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra2')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra3')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra4')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra5')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra6')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra7')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra8')));
    }
}
