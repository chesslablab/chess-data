<?php
namespace PGNChess\Piece;

use PGNChess\Exception\UnknownNotationException;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validate;
use PGNChess\Piece\AbstractPiece;

/**
 * Knight class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Knight extends AbstractPiece
{
    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     */
    public function __construct($color, $square)
    {
        parent::__construct($color, $square, Symbol::KNIGHT);

        $this->position->scope = (object)[
            'jumps' => []
        ];

        $this->scope();
    }

    /**
     * Calculates the knight's scope.
     */
    protected function scope()
    {
        try {
            $file = chr(ord($this->position->current[0]) - 1);
            $rank = (int)$this->position->current[1] + 2;
            if (Validate::square($file.$rank)) {
                $this->position->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position->current[0]) - 2);
            $rank = (int)$this->position->current[1] + 1;
            if (Validate::square($file.$rank)) {
                $this->position->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position->current[0]) - 2);
            $rank = (int)$this->position->current[1] - 1;
            if (Validate::square($file.$rank)) {
                $this->position->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position->current[0]) - 1);
            $rank = (int)$this->position->current[1] - 2;
            if (Validate::square($file.$rank)) {
                $this->position->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position->current[0]) + 1);
            $rank = (int)$this->position->current[1] - 2;
            if (Validate::square($file.$rank)) {
                $this->position->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {

            $file = chr(ord($this->position->current[0]) + 2);
            $rank = (int)$this->position->current[1] - 1;
            if (Validate::square($file.$rank)) {
                $this->position->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position->current[0]) + 2);
            $rank = (int)$this->position->current[1] + 1;
            if (Validate::square($file.$rank)) {
                $this->position->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position->current[0]) + 1);
            $rank = (int)$this->position->current[1] + 2;
            if (Validate::square($file.$rank)) {
                $this->position->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

    }

    public function getLegalMoves()
    {
        $moves = [];

        foreach ($this->getPosition()->scope->jumps as $square) {

            switch(true) {
                case null != $this->getMove() && $this->getMove()->isCapture == false:
                    if (in_array($square, self::$boardStatus->squares->free)) {
                        $moves[] = $square;
                    } elseif (in_array($square, self::$boardStatus->squares->used->{$this->getOppositeColor()})) {
                        $moves[] = $square;
                    }
                    break;

                case null != $this->getMove() && $this->getMove()->isCapture == true:
                    if (in_array($square, self::$boardStatus->squares->used->{$this->getOppositeColor()})) {
                        $moves[] = $square;
                    }
                    break;

                case null == $this->getMove():
                    if (in_array($square, self::$boardStatus->squares->free)) {
                        $moves[] = $square;
                    } elseif (in_array($square, self::$boardStatus->squares->used->{$this->getOppositeColor()})) {
                        $moves[] = $square;
                    }
                    break;
            }
        }

        return $moves;
    }
}
