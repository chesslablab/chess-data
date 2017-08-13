<?php
namespace PGNChess;

use PGNChess\PGN;

/**
 * Castling class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Castling
{
    /**
     * Stores the castling information for further processing.
     *
     * @param string $color
     * @return stdClass
     */
    public static function info($color)
    {
        switch ($color) {
            case Symbol::COLOR_WHITE:
                return (object) [
                    Symbol::PIECE_KING => (object) [
                        PGN::CASTLING_SHORT => (object) [
                            'freeSquares' => (object) [
                                'f' => 'f1',
                                'g' => 'g1'
                            ],
                            'position' => (object) [
                                'current' => 'e1',
                                'next' => 'g1'
                        ]],
                        PGN::CASTLING_LONG => (object) [
                            'freeSquares' => (object) [
                                'b' => 'b1',
                                'c' => 'c1',
                                'd' => 'd1'
                            ],
                            'position' => (object) [
                                'current' => 'e1',
                                'next' => 'c1'
                        ]]
                    ],
                    Symbol::PIECE_ROOK => (object) [
                        PGN::CASTLING_SHORT => (object) [
                            'position' => (object) [
                                'current' => 'h1',
                                'next' => 'f1'
                        ]],
                        PGN::CASTLING_LONG => (object) [
                            'position' => (object) [
                                'current' => 'a1',
                                'next' => 'd1'
                        ]]
                    ]
                ];
                break;

            case Symbol::COLOR_BLACK:
                return (object) [
                    Symbol::PIECE_KING => (object) [
                        PGN::CASTLING_SHORT => (object) [
                            'freeSquares' => (object) [
                                'f' => 'f8',
                                'g' => 'g8'
                            ],
                            'position' => (object) [
                                'current' => 'e8',
                                'next' => 'g8'
                        ]],
                        PGN::CASTLING_LONG => (object) [
                            'freeSquares' => (object) [
                                'b' => 'b8',
                                'c' => 'c8',
                                'd' => 'd8'
                            ],
                            'position' => (object) [
                                'current' => 'e8',
                                'next' => 'c8'
                        ]]
                    ],
                    Symbol::PIECE_ROOK => (object) [
                        PGN::CASTLING_SHORT => (object) [
                            'position' => (object) [
                                'current' => 'h8',
                                'next' => 'f8'
                        ]],
                        PGN::CASTLING_LONG => (object) [
                            'position' => (object) [
                                'current' => 'a8',
                                'next' => 'd8'
                        ]]
                    ]
                ];
                break;
        }
    }
}
