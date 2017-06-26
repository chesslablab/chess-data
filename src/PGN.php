<?php
namespace PGNChess;

/**
 * Generic class for handling information encoded in PGN format.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class PGN
{
    const COLOR_WHITE = 'w';
    const COLOR_BLACK = 'b';

    const PIECE_BISHOP = 'B';
    const PIECE_KING = 'K';
    const PIECE_KNIGHT = 'N';
    const PIECE_PAWN = 'P';
    const PIECE_QUEEN = 'Q';
    const PIECE_ROOK = 'R';

    const CASTLING_SHORT = 'O-O';
    const CASTLING_LONG = 'O-O-O';
    const SQUARE = '[a-h]{1}[1-8]{1}';

    const MOVE_TYPE_KING = 'K' . self::SQUARE;
    const MOVE_TYPE_KING_CASTLING_SHORT = self::CASTLING_SHORT;
    const MOVE_TYPE_KING_CASTLING_LONG = self::CASTLING_LONG;
    const MOVE_TYPE_KING_CAPTURES = 'Kx' . self::SQUARE;
    const MOVE_TYPE_PIECE = '[BRQ]{1}[a-h]{0,1}[1-8]{0,1}' . self::SQUARE;
    const MOVE_TYPE_PIECE_CAPTURES = '[BRQ]{1}[a-h]{0,1}[1-8]{0,1}x' . self::SQUARE;
    const MOVE_TYPE_KNIGHT = 'N[a-h]{0,1}[1-8]{0,1}' . self::SQUARE;
    const MOVE_TYPE_KNIGHT_CAPTURES = 'N[a-h]{0,1}[1-8]{0,1}x' . self::SQUARE;
    const MOVE_TYPE_PAWN = self::SQUARE;
    const MOVE_TYPE_PAWN_CAPTURES = '[a-h]{1}x' . self::SQUARE;

    /**
     * Validates a color in PGN notation.
     *
     * @param string $color
     *
     * @return boolean true if the color is valid; otherwise false
     *
     * @throws \InvalidArgumentException
     */
    public static function color($color)
    {
        if ($color !== self::COLOR_WHITE && $color !== self::COLOR_BLACK)
        {
            throw new \InvalidArgumentException("This is not a valid color: $color.");
        }
        return true;
    }

    /**
     * Validates a board square in PGN notation.
     *
     * @param string $square
     *
     * @return boolean true if the square is valid; otherwise false
     *
     * @throws \InvalidArgumentException
     */
    public static function square($square)
    {
        if (!preg_match('/^' . self::SQUARE . '$/', $square))
        {
            throw new \InvalidArgumentException("This square is not valid: $square.");
        }
        return true;
    }

    /**
     * Converts a valid PGN move into a stdClass object for further processing.
     *
     * @param string $color
     * @param string $pgn
     *
     * @return stdClass
     *
     * @throws \InvalidArgumentException
     */
    static public function objectizeMove($color, $pgn)
    {
        switch(true)
        {
            case preg_match('/^' . self::MOVE_TYPE_KING . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'type' => self::MOVE_TYPE_KING,
                    'color' => $color,
                    'identity' => self::PIECE_KING,
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, -2)
                    ]
                ];
                break;

            case $pgn === self::MOVE_TYPE_KING_CASTLING_SHORT:
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'type' => self::MOVE_TYPE_KING_CASTLING_SHORT,
                    'color' => $color,
                    'identity' => self::PIECE_KING,
                    'position' => self::castling($color)->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->position
                ];
                break;

            case $pgn === self::MOVE_TYPE_KING_CASTLING_LONG:
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'type' => self::MOVE_TYPE_KING_CASTLING_LONG,
                    'color' => $color,
                    'identity' => self::PIECE_KING,
                    'position' => self::castling($color)->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->position
                ];
                break;

            case preg_match('/^' . self::MOVE_TYPE_KING_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'type' => self::MOVE_TYPE_KING_CAPTURES,
                    'color' => $color,
                    'identity' => self::PIECE_KING,
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, -2)
                    ]
                ];
                break;

            case preg_match('/^' . self::MOVE_TYPE_PIECE . '$/', $pgn):
                $position = mb_substr(mb_substr($pgn, 0, -2),1);
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'type' => self::MOVE_TYPE_PIECE,
                    'color' => $color,
                    'identity' => mb_substr($pgn, 0, 1),
                    'position' => (object) [
                        'current' => !empty($position) ? $position : null,
                        'next' => mb_substr($pgn, -2)
                    ]
                ];
                break;

            case preg_match('/^' . self::MOVE_TYPE_KNIGHT . '$/', $pgn):
                $position = mb_substr(mb_substr($pgn, 0, -2),1);
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'type' => self::MOVE_TYPE_KNIGHT,
                    'color' => $color,
                    'identity' => self::PIECE_KNIGHT,
                    'position' => (object) [
                        'current' => !empty($position) ? $position : null,
                        'next' => mb_substr($pgn, -2)
                    ]
                ];
                break;

            case preg_match('/^' . self::MOVE_TYPE_KNIGHT_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'type' => self::MOVE_TYPE_KNIGHT_CAPTURES,
                    'color' => $color,
                    'identity' => self::PIECE_KNIGHT,
                    'position' => (object) [
                        'current' => mb_substr(mb_substr($pgn, 0, -3), 1),
                        'next' => mb_substr($pgn, -2)
                    ]
                ];
                break;

            case preg_match('/^' . self::MOVE_TYPE_PAWN . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'type' => self::MOVE_TYPE_PAWN,
                    'color' => $color,
                    'identity' => self::PIECE_PAWN,
                    'position' => (object) [
                        'current' => mb_substr($pgn, 0, 1),
                        'next' => $pgn
                    ]
                ];
                break;

            case preg_match('/^' . self::MOVE_TYPE_PIECE_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'type' => self::MOVE_TYPE_PIECE_CAPTURES,
                    'color' => $color,
                    'identity' => mb_substr($pgn, 0, 1),
                    'position' => (object) [
                        'current' => mb_substr(mb_substr($pgn, 0, -3), 1),
                        'next' => mb_substr($pgn, -2)
                    ]
                ];
                break;

            case preg_match('/^' . self::MOVE_TYPE_PAWN_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'type' => self::MOVE_TYPE_PAWN_CAPTURES,
                    'color' => $color,
                    'identity' => self::PIECE_PAWN,
                    'position' => (object) [
                        'current' => mb_substr($pgn, 0, 1),
                        'next' => mb_substr($pgn, -2)
                    ]
                ];
                break;

            default:
                throw new \InvalidArgumentException("This PGN move is not valid: $pgn.");
                break;
        }
    }

    /**
     * Stores the castling information in the form of a stdClass object for
     * further processing.
     *
     * @param string $color
     *
     * @return stdClass
     */
    public static function castling($color)
    {
        switch ($color)
        {
            case PGN::COLOR_WHITE:
                return (object) [
                    PGN::PIECE_KING => (object) [
                        PGN::CASTLING_SHORT => (object) [
                            'freeSquares' => (object) [
                                'f' => 'f1',
                                'g' => 'g1'
                            ],
                            'position' => (object) [
                                'current' => 'e1',
                                'next' => 'g1'
                            ]
                        ],
                        PGN::CASTLING_LONG => (object) [
                            'freeSquares' => (object) [
                                'b' => 'b1',
                                'c' => 'c1',
                                'd' => 'd1'
                            ],
                            'position' => (object) [
                                'current' => 'e1',
                                'next' => 'c1'
                            ]
                        ]
                    ],
                    PGN::PIECE_ROOK => (object) [
                        PGN::CASTLING_SHORT => (object) [
                            'position' => (object) [
                                'current' => 'h1',
                                'next' => 'f1'
                            ]
                        ],
                        PGN::CASTLING_LONG => (object) [
                            'position' => (object) [
                                'current' => 'a1',
                                'next' => 'd1'
                            ]
                        ]
                    ]
                ];
                break;

            case PGN::COLOR_BLACK:
                return (object) [
                    PGN::PIECE_KING => (object) [
                        PGN::CASTLING_SHORT => (object) [
                            'freeSquares' => (object) [
                                'f' => 'f8',
                                'g' => 'g8'
                            ],
                            'position' => (object) [
                                'current' => 'e8',
                                'next' => 'g8'
                            ]
                        ],
                        PGN::CASTLING_LONG => (object) [
                            'freeSquares' => (object) [
                                'b' => 'b8',
                                'c' => 'c8',
                                'd' => 'd8'
                            ],
                            'position' => (object) [
                                'current' => 'e8',
                                'next' => 'c8'
                            ]
                        ]
                    ],
                    PGN::PIECE_ROOK => (object) [
                        PGN::CASTLING_SHORT => (object) [
                            'position' => (object) [
                                'current' => 'h8',
                                'next' => 'f8'
                            ]
                        ],
                        PGN::CASTLING_LONG => (object) [
                            'position' => (object) [
                                'current' => 'a8',
                                'next' => 'd8'
                            ]
                        ]
                    ]
                ];
                break;
        }
    }
}
