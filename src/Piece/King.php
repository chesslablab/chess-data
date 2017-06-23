<?php
namespace PGNChess\Piece;

use PGNChess\PGN;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Bishop;

class King extends AbstractPiece
{
    private $castlingInfo;

    private $rook;

    private $bishop;

    public function __construct($color, $square)
    {
        parent::__construct($color, $square, PGN::PIECE_KING);

        switch ($this->color)
        {
            case PGN::COLOR_WHITE:
                $this->castlingInfo = (object) [
                    PGN::PIECE_KING => (object) [
                        PGN::CASTLING_LONG => (object) [
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
                        PGN::CASTLING_SHORT => (object) [
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
                        PGN::CASTLING_LONG => (object) [
                            'move' => (object) [
                                'current' => 'a1',
                                'next' => 'd1'
                            ]
                        ],
                        PGN::CASTLING_SHORT => (object) [
                            'move' => (object) [
                                'current' => 'h1',
                                'next' => 'f1'
                            ]
                        ],
                    ]
                ];
                break;

            case PGN::COLOR_BLACK:
                $this->castlingInfo = (object) [
                    PGN::PIECE_KING => (object) [
                        PGN::CASTLING_LONG => (object) [
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
                        PGN::CASTLING_SHORT => (object) [
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
                        PGN::CASTLING_LONG => (object) [
                            'move' => (object) [
                                'current' => 'a8',
                                'next' => 'd8'
                            ]
                        ],
                        PGN::CASTLING_SHORT => (object) [
                            'move' => (object) [
                                'current' => 'h8',
                                'next' => 'f8'
                            ]
                        ],
                    ]
                ];
                break;
        }

        $this->rook = new Rook($color, $square, PGN::PIECE_ROOK);
        $this->bishop = new Bishop($color, $square, PGN::PIECE_BISHOP);
        $this->scope();
    }

    public function getCastlingInfo()
    {
        return $this->castlingInfo;
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
