<?php
namespace PGNChess\Piece\Type;

/**
 * RookType class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class RookType
{
    const CASTLING_SHORT = 'castling short';
    const CASTLING_LONG = 'castling long';
    const PROMOTED = 'promoted';
    const FAKED = 'faked';

    public static function getChoices()
    {
        return [
            self::CASTLING_SHORT,
            self::CASTLING_LONG,
            self::PROMOTED,
            self::FAKED
        ];
    }
}
