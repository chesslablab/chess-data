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
use PGNChess\Type\RookType;

class StatusTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateDefaultBoardAndCountSquares()
    {
        $board = new Board;

        $this->assertEquals(count($board), 32);
        $this->assertEquals(count($board->getSquares()->used->w), 16);
        $this->assertEquals(count($board->getSquares()->used->b), 16);
    }

    public function testInstantiateCustomBoardAndCountSquares()
    {
        $pieces = [
            new Bishop(Symbol::WHITE, 'c1'),
            new Queen(Symbol::WHITE, 'd1'),
            new King(Symbol::WHITE, 'e1'),
            new Pawn(Symbol::WHITE, 'e2'),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'g8')
        ];

        $castling = (object) [
            'w' => (object) [
                'castled' => false,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => (object) [
                'castled' => false,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(count($board), 7);
        $this->assertEquals(count($board->getSquares()->used->w), 4);
        $this->assertEquals(count($board->getSquares()->used->b), 3);
    }

    public function testPlaySomeMovesAndCheckCastling()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'd4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'c6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Bf4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'd5'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Nc3'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nf6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Bxb8'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Rxb8'));

        $castling = (object) [
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
        ];

        $this->assertEquals($castling, $board->getCastling());
    }

    public function testPlaySomeMovesAndCheckSpace()
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
            $board->play(Convert::toObject(Symbol::WHITE, $moves[0]));
            $board->play(Convert::toObject(Symbol::BLACK, $moves[1]));
        }

        $space = (object) [
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

        $this->assertEquals($space, $board->getControl()->space);
    }
    
    public function testCaptures()
    {
        $captures= (object) [
            'w' => [
                (object) [
                'capturing' => (object) [
                    'identity' => 'P', 
                    'position' => 'b2'],
                'captured' => (object) [
                    'identity' => 'B', 
                    'position' => 'c3']
                ],
                (object) [
                'capturing' => (object) [
                    'identity' => 'P', 
                    'position' => 'e5'],
                'captured' => (object) [
                    'identity' => 'P', 
                    'position' => 'f5'],
                ],
                (object) [
                'capturing' => (object) [
                    'identity' => 'P', 
                    'position' => 'd4'],
                'captured' => (object) [
                    'identity' => 'P', 
                    'position' => 'c5']
                ]
            ],
            'b' => [
                (object) [
                'capturing' => (object) [
                    'identity' => 'B', 
                    'position' => 'b4'],
                'captured' => (object) [
                    'identity' => 'N', 
                    'position' => 'c3']
                ],
                (object) [
                'capturing' => (object) [
                    'identity' => 'R', 
                    'position' => 'f8'],
                'captured' => (object) [
                    'identity' => 'P', 
                    'position' => 'f6'],
                ]
            ]
        ];
        
        $board = new Board;
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'e6')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'd4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'd5')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nc3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Bb4')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'c5')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Qg4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Ne7')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nbc6')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'a3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Bxc3')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'bxc3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Qc7')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Rb1')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'O-O')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Bd3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'f5')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'exf6')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Rxf6')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Qh3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'g6')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Qh4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Rf7')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Qg3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Qa5')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Ng5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Rg7')));
        
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'dxc5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'e5')));
        
        $this->assertEquals($captures, $board->getCaptures());
    }
}
