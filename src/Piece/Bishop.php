<?php
namespace PGNChess\Piece;

use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validator;
use PGNChess\Piece\AbstractPiece;

/**
 * Bishop class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Bishop extends Slider
{
    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     */
    public function __construct($color, $square)
    {
        parent::__construct($color, $square, Symbol::BISHOP);

        $this->position->scope = (object)[
            'upLeft' => [],
            'upRight' => [],
            'bottomLeft' => [],
            'bottomRight' => []
        ];

        $this->scope();
    }

    /**
     * Calculates the bishop's scope.
     */
    protected function scope()
    {
        // top left diagonal
        try {
            $file = chr(ord($this->position->current[0]) - 1);
            $rank = (int)$this->position->current[1] + 1;
            while (Validator::square($file.$rank, true)) {
                $this->position->scope->upLeft[] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank + 1;
            }
        } catch (\InvalidArgumentException $e) {

        }

        // top right diagonal
        try {
            $file = chr(ord($this->position->current[0]) + 1);
            $rank = (int)$this->position->current[1] + 1;
            while (Validator::square($file.$rank, true)) {
                $this->position->scope->upRight[] = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank + 1;
            }
        } catch (\InvalidArgumentException $e) {

        }

        // bottom left diagonal
        try {
            $file = chr(ord($this->position->current[0]) - 1);
            $rank = (int)$this->position->current[1] - 1;
            while (Validator::square($file.$rank, true))
            {
                $this->position->scope->bottomLeft[] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank - 1;
            }
        } catch (\InvalidArgumentException $e) {

        }

        // bottom right diagonal
        try {
            $file = chr(ord($this->position->current[0]) + 1);
            $rank = (int)$this->position->current[1] - 1;
            while (Validator::square($file.$rank, true))
            {
                $this->position->scope->bottomRight[] = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank - 1;
            }
        } catch (\InvalidArgumentException $e) {

        }
    }
}
