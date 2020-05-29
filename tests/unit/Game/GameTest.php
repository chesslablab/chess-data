<?php

namespace PGNChess\Tests\Unit\Game;

use PGNChess\Game;
use PGNChess\PGN\Convert;
use PGNChess\Tests\AbstractUnitTestCase;

class GameTest extends AbstractUnitTestCase
{
    const DATA_FOLDER = __DIR__.'/../../../data/unit';

    protected $game;

    public function setUp()
    {
        $this->game = new Game();
    }

    public function tearDown()
    {
        $this->game = null;
    }

    /**
     * @test
     */
    public function check_status()
    {
        $this->game->play('w', 'd4');
        $this->game->play('b', 'c6');
        $this->game->play('w', 'Bf4');
        $this->game->play('b', 'd5');
        $this->game->play('w', 'Nc3');
        $this->game->play('b', 'Nf6');
        $this->game->play('w', 'Bxb8');
        $this->game->play('b', 'Rxb8');

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

        $this->assertEquals($status, $this->game->status());

        // current turn
        $this->assertEquals($status->turn, $this->game->status()->turn);

        // used/free squares
        $this->assertEquals($status->squares->used, $this->game->status()->squares->used);
        $this->assertEquals($status->squares->free, $this->game->status()->squares->free);

        // white's control
        $this->assertEquals($status->control->space->w, $this->game->status()->control->space->{'w'});
        $this->assertEquals($status->control->attack->w, $this->game->status()->control->attack->{'w'});

        // black's control
        $this->assertEquals($status->control->space->b, $this->game->status()->control->space->{'b'});
        $this->assertEquals($status->control->attack->b, $this->game->status()->control->attack->{'b'});

        // white's castling
        $this->assertEquals($status->castling->w->castled, $this->game->status()->castling->{'w'}->castled);
        $this->assertEquals($status->castling->w->{'O-O'}, $this->game->status()->castling->{'w'}->{'O-O'});
        $this->assertEquals($status->castling->w->{'O-O-O'}, $this->game->status()->castling->{'w'}->{'O-O-O'});

        // black's castling
        $this->assertEquals($status->castling->b->castled, $this->game->status()->castling->{'b'}->castled);
        $this->assertEquals($status->castling->b->{'O-O'}, $this->game->status()->castling->{'b'}->{'O-O'});
        $this->assertEquals($status->castling->b->{'O-O-O'}, $this->game->status()->castling->{'b'}->{'O-O-O'});
    }

    /**
     * @test
     */
    public function count_pieces()
    {
        $this->game->play('w', 'e4');
        $this->game->play('b', 'e5');
        $this->assertEquals(16, count($this->game->pieces('w')));
        $this->assertEquals(16, count($this->game->pieces('b')));

        $this->game->play('w', 'Nf3');
        $this->game->play('b', 'Nc6');
        $this->assertEquals(16, count($this->game->pieces('w')));
        $this->assertEquals(16, count($this->game->pieces('b')));

        $this->game->play('w', 'Bb5');
        $this->game->play('b', 'd6');
        $this->assertEquals(16, count($this->game->pieces('w')));
        $this->assertEquals(16, count($this->game->pieces('b')));

        $this->game->play('w', 'O-O');
        $this->game->play('b', 'a6');
        $this->assertEquals(16, count($this->game->pieces('w')));
        $this->assertEquals(16, count($this->game->pieces('b')));

        $this->game->play('w', 'Bxc6+');
        $this->game->play('b', 'bxc6');
        $this->assertEquals(15, count($this->game->pieces('w')));
        $this->assertEquals(15, count($this->game->pieces('b')));

        $this->game->play('w', 'd4');
        $this->game->play('b', 'exd4');
        $this->assertEquals(14, count($this->game->pieces('w')));
        $this->assertEquals(15, count($this->game->pieces('b')));

        $this->game->play('w', 'Nxd4');
        $this->game->play('b', 'Bd7');
        $this->assertEquals(14, count($this->game->pieces('w')));
        $this->assertEquals(14, count($this->game->pieces('b')));

        $this->game->play('w', 'Re1');
        $this->game->play('b', 'c5');
        $this->assertEquals(14, count($this->game->pieces('w')));
        $this->assertEquals(14, count($this->game->pieces('b')));

        $this->game->play('w', 'Nf3');
        $this->game->play('b', 'Be7');
        $this->assertEquals(14, count($this->game->pieces('w')));
        $this->assertEquals(14, count($this->game->pieces('b')));

        $this->game->play('w', 'Nc3');
        $this->game->play('b', 'c6');
        $this->assertEquals(14, count($this->game->pieces('w')));
        $this->assertEquals(14, count($this->game->pieces('b')));

        $this->game->play('w', 'Bf4');
        $this->game->play('b', 'Be6');
        $this->assertEquals(14, count($this->game->pieces('w')));
        $this->assertEquals(14, count($this->game->pieces('b')));

        $this->game->play('w', 'Qd3');
        $this->game->play('b', 'Nf6');
        $this->assertEquals(14, count($this->game->pieces('w')));
        $this->assertEquals(14, count($this->game->pieces('b')));

        $this->game->play('w', 'Rad1');
        $this->game->play('b', 'd5');
        $this->assertEquals(14, count($this->game->pieces('w')));
        $this->assertEquals(14, count($this->game->pieces('b')));

        $this->game->play('w', 'Ng5');
        $this->game->play('b', 'd4');
        $this->assertEquals(14, count($this->game->pieces('w')));
        $this->assertEquals(14, count($this->game->pieces('b')));

        $this->game->play('w', 'Nxe6');
        $this->game->play('b', 'fxe6');
        $this->assertEquals(13, count($this->game->pieces('w')));
        $this->assertEquals(13, count($this->game->pieces('b')));

        $this->game->play('w', 'Na4');
        $this->game->play('b', 'Qa5');
        $this->assertEquals(13, count($this->game->pieces('w')));
        $this->assertEquals(13, count($this->game->pieces('b')));

        $this->game->play('w', 'b3');
        $this->game->play('b', 'Rd8');
        $this->assertEquals(13, count($this->game->pieces('w')));
        $this->assertEquals(13, count($this->game->pieces('b')));

        $this->game->play('w', 'Nb2');
        $this->game->play('b', 'Nh5');
        $this->assertEquals(13, count($this->game->pieces('w')));
        $this->assertEquals(13, count($this->game->pieces('b')));

        $this->game->play('w', 'Be5');
        $this->game->play('b', 'O-O');
        $this->assertEquals(13, count($this->game->pieces('w')));
        $this->assertEquals(13, count($this->game->pieces('b')));

        $this->game->play('w', 'Nc4');
        $this->game->play('b', 'Qb4');
        $this->assertEquals(13, count($this->game->pieces('w')));
        $this->assertEquals(13, count($this->game->pieces('b')));

        $this->game->play('w', 'Qh3');
        $this->game->play('b', 'g6');
        $this->assertEquals(13, count($this->game->pieces('w')));
        $this->assertEquals(13, count($this->game->pieces('b')));

        $this->game->play('w', 'Qxe6+');
        $this->assertEquals(13, count($this->game->pieces('w')));
        $this->assertEquals(12, count($this->game->pieces('b')));
    }

    /**
     * @test
     */
    public function piece_by_position()
    {
        $piece = (object) [
            'color' => 'b',
            'identity' => 'N',
            'position' => 'b8',
            'moves' => [
                'a6',
                'c6'
            ]
        ];

        $this->assertEquals($piece, $this->game->piece('b8'));
        $this->assertEquals($piece->color, 'b');
        $this->assertEquals($piece->identity, 'N');
        $this->assertEquals($piece->position, 'b8');
        $this->assertEquals($piece->moves, ['a6', 'c6']);
    }

    /**
     * @test
     */
    public function black_pieces()
    {
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

        $this->assertEquals($blackPieces, $this->game->pieces('b'));
        $this->assertEquals($blackPieces[1]->identity, 'N');
        $this->assertEquals($blackPieces[1]->position, 'b8');
        $this->assertEquals($blackPieces[1]->moves, ['a6', 'c6']);
    }

    /**
     * @test
     */
    public function empty_square()
    {
        $piece = $this->game->piece('e3');

        $this->assertNull($piece);
    }

    /**
     * @dataProvider playSampleGamesData
     * @test
     */
    public function play_sample_games($filename)
    {
        $pgn = file_get_contents(self::DATA_FOLDER."/$filename");

        $pairs = array_filter(preg_split('/[0-9]+\./', $pgn));

        $moves = [];
        foreach ($pairs as $pair) {
            $moves[] = array_values(array_filter(explode(' ', $pair)));
        }

        $moves = array_values(array_filter($moves));

        for ($i = 0; $i < count($moves); ++$i) {
            $whiteMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][0]));
            $this->assertEquals(true, $this->game->play('w', $whiteMove));
            if (isset($moves[$i][1])) {
                $blackMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][1]));
                $this->assertEquals(true, $this->game->play('b', $blackMove));
            }
        }
    }

    public function playSampleGamesData()
    {
        $data = [];
        for ($i = 1; $i <= 85; ++$i) {
            $i <= 9 ? $data[] = ["game-0$i.pgn"] : $data[] = ["game-$i.pgn"];
        }

        return $data;
    }
}
