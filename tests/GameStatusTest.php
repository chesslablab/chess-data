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
    }
}
