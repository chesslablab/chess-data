<?php
namespace PGNChess;

use PGNChess\PGN;

/**
 * Performs some statistics operations regarding the squares of the board.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Squares
{
    public static function stats(array $pieces)
    {
        return (object) [
            'used' => self::getUsed($pieces),
            'free' => self::getFree($pieces)
        ];
    }

    /**
     * Returns all the board's squares.
     *
     * @return array
     */
    private static function getAll()
    {
        $squares = [];
        for($i=0; $i<8; $i++)
        {
            for($j=1; $j<=8; $j++)
            {
                $squares[] = chr((ord('a') + $i)) . $j;
            }
        }
        return $squares;
    }

    /**
     * Returns the squares currently being used by both players.
     *
     * @return array
     */
    private static function getUsed(array $pieces)
    {
        $squares = (object) [
            PGN::COLOR_WHITE => [],
            PGN::COLOR_BLACK => []
        ];
        foreach ($pieces as $piece)
        {
            $squares->{$piece->getColor()}[] = $piece->getPosition()->current;
        }
        return $squares;
    }

    /**
     * Returns the free squares.
     *
     * @return array
     */
    private static function getFree(array $pieces)
    {
        $usedSquares = self::getUsed($pieces);
        return array_values(
            array_diff(
                self::getAll(),
                array_merge($usedSquares->{PGN::COLOR_WHITE}, $usedSquares->{PGN::COLOR_BLACK})
            )
        );
    }
}
