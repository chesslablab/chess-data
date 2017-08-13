<?php
namespace PGNChess\PGN;

use PGNChess\PGN\Symbol;

/**
 * Validates PGN notation.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Validator
{
    /**
     * Validates a color.
     *
     * @param string $color
     * @return boolean true if the color is valid; otherwise false
     * @throws \InvalidArgumentException
     */
    public static function color($color)
    {
        if ($color !== Symbol::COLOR_WHITE && $color !== Symbol::COLOR_BLACK) {
            throw new \InvalidArgumentException("This is not a valid color: $color.");
        }

        return true;
    }

    /**
     * Validates a square.
     *
     * @param string $square
     * @return boolean true if the square is valid; otherwise false
     * @throws \InvalidArgumentException
     */
    public static function square($square)
    {
        if (!preg_match('/^' . Symbol::SQUARE . '$/', $square)) {
            throw new \InvalidArgumentException("This square is not valid: $square.");
        }

        return true;
    }
}
