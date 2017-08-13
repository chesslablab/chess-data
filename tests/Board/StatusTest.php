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

class StatusTest extends \PHPUnit_Framework_TestCase
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
            new Bishop(Symbol::COLOR_WHITE, 'c1'),
            new Queen(Symbol::COLOR_WHITE, 'd1'),
            new King(Symbol::COLOR_WHITE, 'e1'),
            new Pawn(Symbol::COLOR_WHITE, 'e2'),
            new King(Symbol::COLOR_BLACK, 'e8'),
            new Bishop(Symbol::COLOR_BLACK, 'f8'),
            new Knight(Symbol::COLOR_BLACK, 'g8')
        ];
        $board = new Board($pieces);
        $this->assertEquals(count($board), 7);
        $this->assertEquals(count($board->getStatus()->squares->used->w), 4);
        $this->assertEquals(count($board->getStatus()->squares->used->b), 3);
    }

    public function testPlayGame01AndCheckStatus()
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
            $board->play(Converter::toObject(Symbol::COLOR_WHITE, $moves[0]));
            $board->play(Converter::toObject(Symbol::COLOR_BLACK, $moves[1]));
        }
        $example = (object) [
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
        $this->assertEquals($example, $board->getStatus()->control->space);
    }
}
