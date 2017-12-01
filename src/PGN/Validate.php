<?php
namespace PGNChess\PGN;

use PGNChess\Exception\UnknownNotationException;
use PGNChess\PGN\Symbol;

/**
 * Validates PGN symbols.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Validate
{
    /**
     * Validates a color.
     *
     * @param string $color
     * @return string if the color is valid
     * @throws UnknownNotationException
     */
    public static function color($color)
    {
        if ($color !== Symbol::WHITE && $color !== Symbol::BLACK) {
            throw new UnknownNotationException("This is not a valid color: $color.");
        }

        return $color;
    }

    /**
     * Validates a square.
     *
     * @param string $square
     * @return string if the square is valid
     * @throws UnknownNotationException
     */
    public static function square($square)
    {
        if (!preg_match('/^' . Symbol::SQUARE . '$/', $square)) {
            throw new UnknownNotationException("This square is not valid: $square.");
        }

        return $square;
    }
}
