<?php
namespace PGNChess\PGN;

use PGNChess\PGN\Notation;

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
        if ($color !== Notation::COLOR_WHITE && $color !== Notation::COLOR_BLACK) {
            throw new \InvalidArgumentException("This is not a valid color: $color.");
        }

        return true;
    }

    /**
     * Validates a board square.
     *
     * @param string $square
     * @return boolean true if the square is valid; otherwise false
     * @throws \InvalidArgumentException
     */
    public static function square($square)
    {
        if (!preg_match('/^' . Notation::SQUARE . '$/', $square)) {
            throw new \InvalidArgumentException("This square is not valid: $square.");
        }

        return true;
    }
}
