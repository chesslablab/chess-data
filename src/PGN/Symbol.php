<?php
namespace PGNChess\PGN;

/**
 * Encodes symbols in PGN format.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Symbol
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
    const CHECK = '[\+\#]{0,1}';
}
