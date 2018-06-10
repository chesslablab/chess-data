<?php

namespace PGNChess\Tests\Game;

use PGNChess\Game;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    /**
     * @test
     */
    public function game_check_status()
    {
        $game = new Game;

        $game->play(Symbol::WHITE, 'd4');
        $game->play(Symbol::BLACK, 'c6');
        $game->play(Symbol::WHITE, 'Bf4');
        $game->play(Symbol::BLACK, 'd5');
        $game->play(Symbol::WHITE, 'Nc3');
        $game->play(Symbol::BLACK, 'Nf6');
        $game->play(Symbol::WHITE, 'Bxb8');
        $game->play(Symbol::BLACK, 'Rxb8');

        $status = (object) [
            'turn' => 'w',
            'squares' => (object) [
                'used' => (object) [
                    'w' => [
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
                    'b' => [
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
                    'w' => [
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
                    'b' => [
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
                    'w' => ['d5'],
                    'b' => []
                ]
            ],
            'castling' => (object) [
                'w' => (object) [
                    'castled' => false,
                    'O-O' => true,
                    'O-O-O' => true
                ],
                'b' => (object) [
                    'castled' => false,
                    'O-O' => true,
                    'O-O-O' => false
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
        $this->assertEquals($status->control->space->w, $game->status()->control->space->{Symbol::WHITE});
        $this->assertEquals($status->control->attack->w, $game->status()->control->attack->{Symbol::WHITE});

        // black's control
        $this->assertEquals($status->control->space->b, $game->status()->control->space->{Symbol::BLACK});
        $this->assertEquals($status->control->attack->b, $game->status()->control->attack->{Symbol::BLACK});

        // white's castling
        $this->assertEquals($status->castling->w->castled, $game->status()->castling->{Symbol::WHITE}->castled);
        $this->assertEquals($status->castling->w->{'O-O'}, $game->status()->castling->{Symbol::WHITE}->{Symbol::CASTLING_SHORT});
        $this->assertEquals($status->castling->w->{'O-O-O'}, $game->status()->castling->{Symbol::WHITE}->{Symbol::CASTLING_LONG});

        // black's castling
        $this->assertEquals($status->castling->b->castled, $game->status()->castling->{Symbol::BLACK}->castled);
        $this->assertEquals($status->castling->b->{'O-O'}, $game->status()->castling->{Symbol::BLACK}->{Symbol::CASTLING_SHORT});
        $this->assertEquals($status->castling->b->{'O-O-O'}, $game->status()->castling->{Symbol::BLACK}->{Symbol::CASTLING_LONG});
    }

    /**
     * @test
     */
    public function count_pieces() {

        $game = new Game;

        $game->play(Symbol::WHITE, 'e4');
        $game->play(Symbol::BLACK, 'e5');
        $this->assertEquals(16, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(16, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Nf3');
        $game->play(Symbol::BLACK, 'Nc6');
        $this->assertEquals(16, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(16, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Bb5');
        $game->play(Symbol::BLACK, 'd6');
        $this->assertEquals(16, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(16, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'O-O');
        $game->play(Symbol::BLACK, 'a6');
        $this->assertEquals(16, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(16, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Bxc6+');
        $game->play(Symbol::BLACK, 'bxc6');
        $this->assertEquals(15, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(15, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'd4');
        $game->play(Symbol::BLACK, 'exd4');
        $this->assertEquals(14, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(15, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Nxd4');
        $game->play(Symbol::BLACK, 'Bd7');
        $this->assertEquals(14, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(14, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Re1');
        $game->play(Symbol::BLACK, 'c5');
        $this->assertEquals(14, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(14, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Nf3');
        $game->play(Symbol::BLACK, 'Be7');
        $this->assertEquals(14, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(14, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Nc3');
        $game->play(Symbol::BLACK, 'c6');
        $this->assertEquals(14, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(14, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Bf4');
        $game->play(Symbol::BLACK, 'Be6');
        $this->assertEquals(14, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(14, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Qd3');
        $game->play(Symbol::BLACK, 'Nf6');
        $this->assertEquals(14, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(14, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Rad1');
        $game->play(Symbol::BLACK, 'd5');
        $this->assertEquals(14, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(14, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Ng5');
        $game->play(Symbol::BLACK, 'd4');
        $this->assertEquals(14, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(14, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Nxe6');
        $game->play(Symbol::BLACK, 'fxe6');
        $this->assertEquals(13, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(13, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Na4');
        $game->play(Symbol::BLACK, 'Qa5');
        $this->assertEquals(13, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(13, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'b3');
        $game->play(Symbol::BLACK, 'Rd8');
        $this->assertEquals(13, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(13, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Nb2');
        $game->play(Symbol::BLACK, 'Nh5');
        $this->assertEquals(13, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(13, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Be5');
        $game->play(Symbol::BLACK, 'O-O');
        $this->assertEquals(13, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(13, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Nc4');
        $game->play(Symbol::BLACK, 'Qb4');
        $this->assertEquals(13, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(13, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Qh3');
        $game->play(Symbol::BLACK, 'g6');
        $this->assertEquals(13, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(13, count($game->pieces(Symbol::BLACK)));

        $game->play(Symbol::WHITE, 'Qxe6+');
        $this->assertEquals(13, count($game->pieces(Symbol::WHITE)));
        $this->assertEquals(12, count($game->pieces(Symbol::BLACK)));
    }

    /**
     * @test
     */
    public function piece_by_position()
    {
        $game = new Game;

        $piece = (object) [
            'color' => 'b',
            'identity' => 'N',
            'position' => 'b8',
            'moves' => [
                'a6',
                'c6'
            ]
        ];

        $this->assertEquals($piece, $game->piece('b8'));
        $this->assertEquals($piece->color, Symbol::BLACK);
        $this->assertEquals($piece->identity, Symbol::KNIGHT);
        $this->assertEquals($piece->position, 'b8');
        $this->assertEquals($piece->moves, ['a6', 'c6']);
    }

    /**
     * @test
     */
    public function black_pieces()
    {
        $game = new Game;

        $blackPieces = [
            (object) [
                'identity' => 'R',
                'position' => 'a8',
                'moves' => []
            ],
            (object) [
                'identity' => 'N',
                'position' => 'b8',
                'moves' => [
                    'a6',
                    'c6'
                ]
            ],
            (object) [
                'identity' => 'B',
                'position' => 'c8',
                'moves' => []
            ],
            (object) [
                'identity' => 'Q',
                'position' => 'd8',
                'moves' => []
            ],
            (object) [
                'identity' => 'K',
                'position' => 'e8',
                'moves' => []
            ],
            (object) [
                'identity' => 'B',
                'position' => 'f8',
                'moves' => []
            ],
            (object) [
                'identity' => 'N',
                'position' => 'g8',
                'moves' => [
                    'f6',
                    'h6'
                ]
            ],
            (object) [
                'identity' => 'R',
                'position' => 'h8',
                'moves' => []
            ],
            (object) [
                'identity' => 'P',
                'position' => 'a7',
                'moves' => [
                    'a6',
                    'a5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'b7',
                'moves' => [
                    'b6',
                    'b5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'c7',
                'moves' => [
                    'c6',
                    'c5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'd7',
                'moves' => [
                    'd6',
                    'd5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'e7',
                'moves' => [
                    'e6',
                    'e5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'f7',
                'moves' => [
                    'f6',
                    'f5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'g7',
                'moves' => [
                    'g6',
                    'g5'
                ]
            ],
            (object) [
                'identity' => 'P',
                'position' => 'h7',
                'moves' => [
                    'h6',
                    'h5'
                ]
            ]
        ];

        $this->assertEquals($blackPieces, $game->pieces(Symbol::BLACK));
        $this->assertEquals($blackPieces[1]->identity, Symbol::KNIGHT);
        $this->assertEquals($blackPieces[1]->position, 'b8');
        $this->assertEquals($blackPieces[1]->moves, ['a6', 'c6']);
    }

    /**
     * @test
     */
    public function empty_square()
    {
        $game = new Game();
        $piece = $game->piece('e3');

        $this->assertNull($piece);
    }
}
