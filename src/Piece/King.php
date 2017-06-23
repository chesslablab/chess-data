<?php
namespace PGNChess\Piece;

use PGNChess\PGN;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Bishop;

class King extends AbstractPiece
{
    private $castling;

    private $rook;

    private $bishop;

    public function __construct($color, $position)
    {
        parent::__construct($color, $position, PGN::PIECE_KING);

        switch ($this->color)
        {
            case PGN::COLOR_WHITE:
                $this->castling = (object) [
                    PGN::PIECE_KING => (object) [
                        'long' => (object) [
                            'freeSquares' => (object) [
                                'b' => 'b1',
                                'c' => 'c1',
                                'd' => 'd1'
                            ],
                            'move' => (object) [
                                'current' => 'e1',
                                'next' => 'c1'
                            ]
                        ],
                        'short' => (object) [
                            'freeSquares' => (object) [
                                'f' => 'f1',
                                'g' => 'g1'
                            ],
                            'move' => (object) [
                                'current' => 'e1',
                                'next' => 'g1'
                            ]
                        ],
                    ],
                    PGN::PIECE_ROOK => (object) [
                        'long' => (object) [
                            'move' => (object) [
                                'current' => 'a1',
                                'next' => 'd1'
                            ]
                        ],
                        'short' => (object) [
                            'move' => (object) [
                                'current' => 'h1',
                                'next' => 'f1'
                            ]
                        ],
                    ]
                ];
                break;

            case PGN::COLOR_BLACK:
                $this->castling = (object) [
                    PGN::PIECE_KING => (object) [
                        'long' => (object) [
                            'freeSquares' => (object) [
                                'b' => 'b8',
                                'c' => 'c8',
                                'd' => 'd8'
                            ],
                            'move' => (object) [
                                'current' => 'e8',
                                'next' => 'c8'
                            ]
                        ],
                        'short' => (object) [
                            'freeSquares' => (object) [
                                'f' => 'f8',
                                'g' => 'g8'
                            ],
                            'move' => (object) [
                                'current' => 'e8',
                                'next' => 'g8'
                            ]
                        ],
                    ],
                    PGN::PIECE_ROOK => (object) [
                        'long' => (object) [
                            'move' => (object) [
                                'current' => 'a8',
                                'next' => 'd8'
                            ]
                        ],
                        'short' => (object) [
                            'move' => (object) [
                                'current' => 'h8',
                                'next' => 'f8'
                            ]
                        ],
                    ]
                ];
                break;
        }

        $this->rook = new Rook($color, $position, PGN::PIECE_ROOK);
        $this->bishop = new Bishop($color, $position, PGN::PIECE_BISHOP);
        $this->scope();
    }

    public function getCastling()
    {
        return $this->castling;
    }

    protected function scope()
    {
        $scope =  array_merge(
            (array) $this->rook->getPosition()->scope,
            (array) $this->bishop->getPosition()->scope
        );
        foreach($scope as $key => $val)
        {
            $scope[$key] = !empty($val[0]) ? $val[0] : null;
        }
        $this->position->scope = (object) $scope;
    }
}
