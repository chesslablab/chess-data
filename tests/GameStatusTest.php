<?php
namespace PGNChess\Tests;

use PGNChess\Game;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;

class GameStatusTest extends \PHPUnit_Framework_TestCase
{
    public function testPlaySomeMovesAndCheckStatus()
    {
        $game = new Game;

        $game->play(Convert::toObject(Symbol::WHITE, 'd4'));
        $game->play(Convert::toObject(Symbol::BLACK, 'c6'));
        $game->play(Convert::toObject(Symbol::WHITE, 'Bf4'));
        $game->play(Convert::toObject(Symbol::BLACK, 'd5'));
        $game->play(Convert::toObject(Symbol::WHITE, 'Nc3'));
        $game->play(Convert::toObject(Symbol::BLACK, 'Nf6'));
        $game->play(Convert::toObject(Symbol::WHITE, 'Bxb8'));
        $game->play(Convert::toObject(Symbol::BLACK, 'Rxb8'));

        $status = (object) [
            'turn' => Symbol::WHITE,
            'squares' => (object) [
                'used' => (object) [
                    Symbol::WHITE => [
                        'a1',
                        'd1',
                        'e1',
                        'f1',
                        'g1',
                        'h1',
                        'a2',
                        'b2',
                        'c2',
                        'e2',
                        'f2',
                        'g2',
                        'h2',
                        'd4',
                        'c3'
                    ],
                    Symbol::BLACK => [
                        'c8',
                        'd8',
                        'e8',
                        'f8',
                        'h8',
                        'a7',
                        'b7',
                        'e7',
                        'f7',
                        'g7',
                        'h7',
                        'c6',
                        'd5',
                        'f6',
                        'b8'
                    ]
                ],
                'free' => [
                    'a3',
                    'a4',
                    'a5',
                    'a6',
                    'a8',
                    'b1',
                    'b3',
                    'b4',
                    'b5',
                    'b6',
                    'c1',
                    'c4',
                    'c5',
                    'c7',
                    'd2',
                    'd3',
                    'd6',
                    'd7',
                    'e3',
                    'e4',
                    'e5',
                    'e6',
                    'f3',
                    'f4',
                    'f5',
                    'g3',
                    'g4',
                    'g5',
                    'g6',
                    'g8',
                    'h3',
                    'h4',
                    'h5',
                    'h6'
                ]
            ],
            'control' => (object) [
                'space' => (object) [
                    Symbol::WHITE => [
                        'a3',
                        'a4',
                        'b1',
                        'b3',
                        'b5',
                        'c1',
                        'c5',
                        'd2',
                        'd3',
                        'e3',
                        'e4',
                        'e5',
                        'f3',
                        'g3',
                        'h3'
                    ],
                    Symbol::BLACK => [
                        'a5',
                        'a6',
                        'a8',
                        'b5',
                        'b6',
                        'c4',
                        'c7',
                        'd6',
                        'd7',
                        'e4',
                        'e6',
                        'f5',
                        'g4',
                        'g6',
                        'g8',
                        'h3',
                        'h5',
                        'h6'
                    ]
                ],
                'attack' => (object) [
                    Symbol::WHITE => ['d5'],
                    Symbol::BLACK => []
                ]
            ],
            'castling' => (object) [
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
            ],
            'previousMove' => (object) [
                Symbol::WHITE => (object) [
                    'identity' => Symbol::BISHOP,
                    'position' => (object) [
                        'current' => null,
                        'next' => 'b8'
                    ]
                ],
                Symbol::BLACK => (object) [
                    'identity' => Symbol::ROOK,
                    'position' => (object) [
                        'current' => null,
                        'next' => 'b8'
                    ]
                ]
            ]
        ];

        $this->assertEquals($status, $game->status());

        // current turn
        $this->assertEquals($status->turn, $game->status()->turn);

        // used/free squares
        $this->assertEquals($status->squares->used, $game->status()->squares->used);
        $this->assertEquals($status->squares->free, $game->status()->squares->free);

        // white's control
        $this->assertEquals($status->control->space->{Symbol::WHITE}, $game->status()->control->space->{Symbol::WHITE});
        $this->assertEquals($status->control->attack->{Symbol::WHITE}, $game->status()->control->attack->{Symbol::WHITE});

        // black's control
        $this->assertEquals($status->control->space->{Symbol::BLACK}, $game->status()->control->space->{Symbol::BLACK});
        $this->assertEquals($status->control->attack->{Symbol::BLACK}, $game->status()->control->attack->{Symbol::BLACK});

        // white's castling
        $this->assertEquals($status->castling->{Symbol::WHITE}->castled, $game->status()->castling->{Symbol::WHITE}->castled);
        $this->assertEquals($status->castling->{Symbol::WHITE}->{Symbol::CASTLING_SHORT}, $game->status()->castling->{Symbol::WHITE}->{Symbol::CASTLING_SHORT});
        $this->assertEquals($status->castling->{Symbol::WHITE}->{Symbol::CASTLING_LONG}, $game->status()->castling->{Symbol::WHITE}->{Symbol::CASTLING_LONG});

        // black's castling
        $this->assertEquals($status->castling->{Symbol::BLACK}->castled, $game->status()->castling->{Symbol::BLACK}->castled);
        $this->assertEquals($status->castling->{Symbol::BLACK}->{Symbol::CASTLING_SHORT}, $game->status()->castling->{Symbol::BLACK}->{Symbol::CASTLING_SHORT});
        $this->assertEquals($status->castling->{Symbol::BLACK}->{Symbol::CASTLING_LONG}, $game->status()->castling->{Symbol::BLACK}->{Symbol::CASTLING_LONG});

        // white's previous move
        $this->assertEquals($status->previousMove->{Symbol::WHITE}->identity, $game->status()->previousMove->{Symbol::WHITE}->identity);
        $this->assertEquals($status->previousMove->{Symbol::WHITE}->position->next, $game->status()->previousMove->{Symbol::WHITE}->position->next);

        // black's previous move
        $this->assertEquals($status->previousMove->{Symbol::BLACK}->identity, $game->status()->previousMove->{Symbol::BLACK}->identity);
        $this->assertEquals($status->previousMove->{Symbol::BLACK}->position->next, $game->status()->previousMove->{Symbol::BLACK}->position->next);
    }

    public function testGetPieceByPosition()
    {
        $game = new Game;

        $piece = (object) [
            'color' => 'b',
            'identity' => 'N',
            'position' => 'b8',
            'squares' => [
                'a6',
                'c6'
            ]
        ];

        $this->assertEquals($piece, $game->getPieceByPosition('b8'));

        $this->assertEquals($piece->color, Symbol::BLACK);
        $this->assertEquals($piece->identity, Symbol::KNIGHT);
        $this->assertEquals($piece->position, 'b8');
        $this->assertEquals($piece->squares, ['a6', 'c6']);
    }

    public function testGetBlackPieces()
    {
        $game = new Game;

        $blackPieces = [
            (object) [
                'identity' => 'R',
                'position' => 'a8',
                'squares' => []
            ],
            (object) [
                'identity' => 'N',
                'position' => 'b8',
                'squares' => [
                    'a6',
                    'c6'
                ]
            ],
            (object) [
                'identity' => 'B',
                'position' => 'c8',
                'squares' => []
            ],
            (object) [
                'identity' => 'Q',
                'position' => 'd8',
                'squares' => []
            ],
            (object) [
                'identity' => 'K',
                'position' => 'e8',
                'squares' => []
            ],
            (object) [
                'identity' => 'B',
                'position' => 'f8',
                'squares' => []
            ],
            (object) [
                'identity' => 'N',
                'position' => 'g8',
                'squares' => [
                    'f6',
                    'h6'
                ]
            ],
            (object) [
                'identity' => 'R',
                'position' => 'h8',
                'squares' => []
            ],
            (object) [
                'identity' => 'P',
                'position' => 'a7',
                'squares' => [
                    'a6',
                    'a5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'b7',
                'squares' => [
                    'b6',
                    'b5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'c7',
                'squares' => [
                    'c6',
                    'c5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'd7',
                'squares' => [
                    'd6',
                    'd5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'e7',
                'squares' => [
                    'e6',
                    'e5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'f7',
                'squares' => [
                    'f6',
                    'f5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'g7',
                'squares' => [
                    'g6',
                    'g5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'h7',
                'squares' => [
                    'h6',
                    'h5'
                ]
            ]
        ];

        $this->assertEquals($blackPieces, $game->getPiecesByColor(Symbol::BLACK));

        $this->assertEquals($blackPieces[1]->identity, Symbol::KNIGHT);
        $this->assertEquals($blackPieces[1]->position, 'b8');
        $this->assertEquals($blackPieces[1]->squares, ['a6', 'c6']);
    }
}
