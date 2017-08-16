<?php
namespace PGNChess\Tests\Board;

use PGNChess\Board;
use PGNChess\Exception\CastlingException;
use PGNChess\Game;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Type\RookType;

class AnalyzeTest extends \PHPUnit_Framework_TestCase
{
    public function testCheck()
    {
        $pieces = [
            new Rook(Symbol::WHITE, 'a7', RookType::CASTLING_LONG),
            new Pawn(Symbol::WHITE, 'd4'),
            new Queen(Symbol::WHITE, 'e3'),
            new King(Symbol::WHITE, 'g1'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new King(Symbol::BLACK, 'e8'),
            new Knight(Symbol::BLACK, 'e4'),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Rook(Symbol::BLACK, 'g5', RookType::CASTLING_LONG),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
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

        $game = new Game;
        $board = new Board($pieces, $castling);
        $game->setBoard($board);

        $this->assertEquals(true, $game->play(Convert::toObject(Symbol::WHITE, 'Ra8+')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(true, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(false, $game->play(Convert::toObject(Symbol::BLACK, 'Kd8')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(true, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(false, $game->play(Convert::toObject(Symbol::BLACK, 'Kf8')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(true, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(true, $game->play(Convert::toObject(Symbol::BLACK, 'Ke7')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(false, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(true, $game->play(Convert::toObject(Symbol::WHITE, 'h3')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(false, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(false, $game->play(Convert::toObject(Symbol::BLACK, 'Nc2')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(false, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(true, $game->play(Convert::toObject(Symbol::BLACK, 'Rxg2+')));
        $this->assertEquals(true, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(false, $game->isChecked(Symbol::BLACK));
    }

    public function testCheckandCheckmate()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'd5'),
            new Queen(Symbol::WHITE, 'f5'),
            new King(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'h8', RookType::CASTLING_LONG),
            new King(Symbol::BLACK, 'e7'),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
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

        $game = new Game;
        $board = new Board($pieces, $castling);
        $game->setBoard($board);

        $this->assertEquals(true, $game->play(Convert::toObject(Symbol::WHITE, 'd6+')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(true, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(false, $game->isMated(Symbol::WHITE));
        $this->assertEquals(false, $game->isMated(Symbol::BLACK));
        $this->assertEquals(false, $game->play(Convert::toObject(Symbol::BLACK, 'Kd7')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(true, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(false, $game->isMated(Symbol::WHITE));
        $this->assertEquals(false, $game->isMated(Symbol::BLACK));
        $this->assertEquals(false, $game->play(Convert::toObject(Symbol::BLACK, 'Ke6')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(true, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(false, $game->isMated(Symbol::WHITE));
        $this->assertEquals(false, $game->isMated(Symbol::BLACK));
        $this->assertEquals(true, $game->play(Convert::toObject(Symbol::BLACK, 'Kxd6')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(false, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(false, $game->isMated(Symbol::WHITE));
        $this->assertEquals(false, $game->isMated(Symbol::BLACK));
        $this->assertEquals(true, $game->play(Convert::toObject(Symbol::WHITE, 'Re8')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(false, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(false, $game->isMated(Symbol::WHITE));
        $this->assertEquals(false, $game->isMated(Symbol::BLACK));
        $this->assertEquals(true, $game->play(Convert::toObject(Symbol::BLACK, 'Kc7')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(false, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(false, $game->isMated(Symbol::WHITE));
        $this->assertEquals(false, $game->isMated(Symbol::BLACK));
        $this->assertEquals(true, $game->play(Convert::toObject(Symbol::WHITE, 'Re7+')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(true, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(false, $game->isMated(Symbol::WHITE));
        $this->assertEquals(false, $game->isMated(Symbol::BLACK));
        $this->assertEquals(true, $game->play(Convert::toObject(Symbol::BLACK, 'Kd8')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(false, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(false, $game->isMated(Symbol::WHITE));
        $this->assertEquals(false, $game->isMated(Symbol::BLACK));
        $this->assertEquals(true, $game->play(Convert::toObject(Symbol::WHITE, 'Qd7#')));
        $this->assertEquals(false, $game->isChecked(Symbol::WHITE));
        $this->assertEquals(true, $game->isChecked(Symbol::BLACK));
        $this->assertEquals(false, $game->isMated(Symbol::WHITE));
        $this->assertEquals(true, $game->isMated(Symbol::BLACK));
    }

    // Here comes a batch of castling tests.
    //
    // Given the following custom board:
    //
    //     $pieces = [
    //         new Pawn(Symbol::WHITE, 'a2'),
    //         new Pawn(Symbol::WHITE, 'a3'),
    //         new Pawn(Symbol::WHITE, 'c3'),
    //         new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
    //         new King(Symbol::WHITE, 'f3'), // in check!
    //         new Pawn(Symbol::BLACK, 'a6'),
    //         new Pawn(Symbol::BLACK, 'b5'),
    //         new Pawn(Symbol::BLACK, 'c4'),
    //         new Knight(Symbol::BLACK, 'd3'),
    //         new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
    //         new King(Symbol::BLACK, 'g5'),
    //         new Pawn(Symbol::BLACK, 'h7')
    //    ];
    //
    // A valid castling object is:
    //
    //     $castling = (object) [
    //         Symbol::WHITE => (object) [
    //             'castled' => true,
    //              Symbol::CASTLING_SHORT => false,
    //              Symbol::CASTLING_LONG => false
    //         ],
    //         Symbol::BLACK => (object) [
    //             'castled' => true,
    //             Symbol::CASTLING_SHORT => false,
    //             Symbol::CASTLING_LONG => false
    //         ]
    //     ];

    public function testInvalidWhiteShortCastling()
    {
        $this->expectException(CastlingException::class);

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
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    public function testInvalidWhiteLongCastling()
    {
        $this->expectException(CastlingException::class);

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
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    public function testInvalidBlackShortCastling()
    {
        $this->expectException(CastlingException::class);

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
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    public function testInvalidBlackLongCastling()
    {
        $this->expectException(CastlingException::class);

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
                Symbol::CASTLING_LONG => true
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    public function testEmptyCastlingObject()
    {
        $this->expectException(CastlingException::class);

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
    }

    public function testEmptyWhiteCastlingObject()
    {
        $this->expectException(CastlingException::class);

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
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => true
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    public function testEmptyBlackCastlingObject()
    {
        $this->expectException(CastlingException::class);

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
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    public function testInvalidWhiteCastlingObjectNoCastledProperty()
    {
        $this->expectException(CastlingException::class);

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
    }

    public function testInvalidWhiteCastlingObjectNoCastlingShortProperty()
    {
        $this->expectException(CastlingException::class);

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
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    public function testInvalidWhiteCastlingObjectNoCastlingLongProperty()
    {
        $this->expectException(CastlingException::class);

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
                Symbol::CASTLING_SHORT => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
    }
}
