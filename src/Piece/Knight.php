<?php
namespace PGNChess\Piece;

use PGNChess\PGN;
use PGNChess\Piece\AbstractPiece;

/**
 * Class that represents a knight.
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
        parent::__construct($color, $square, PGN::PIECE_KNIGHT);
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
        try
        {
            $file = chr(ord($this->position->current[0]) - 1);
            $rank = (int)$this->position->current[1] + 2;
            if(PGN::square($file.$rank, true))
            {
                $this->position->scope->jumps[] = $file . $rank;
            }
        }
        catch (\InvalidArgumentException $e) {}

        try
        {
            $file = chr(ord($this->position->current[0]) - 2);
            $rank = (int)$this->position->current[1] + 1;
            if(PGN::square($file.$rank, true))
            {
                $this->position->scope->jumps[] = $file . $rank;
            }
        }
        catch (\InvalidArgumentException $e) {}

        try
        {
            $file = chr(ord($this->position->current[0]) - 2);
            $rank = (int)$this->position->current[1] - 1;
            if(PGN::square($file.$rank, true))
            {
                $this->position->scope->jumps[] = $file . $rank;
            }
        }
        catch (\InvalidArgumentException $e) {}

        try
        {
            $file = chr(ord($this->position->current[0]) - 1);
            $rank = (int)$this->position->current[1] - 2;
            if(PGN::square($file.$rank, true))
            {
                $this->position->scope->jumps[] = $file . $rank;
            }
        }
        catch (\InvalidArgumentException $e) {}

        try
        {
            $file = chr(ord($this->position->current[0]) + 1);
            $rank = (int)$this->position->current[1] - 2;
            if(PGN::square($file.$rank, true))
            {
                $this->position->scope->jumps[] = $file . $rank;
            }
        }
        catch (\InvalidArgumentException $e) {}

        try
        {

            $file = chr(ord($this->position->current[0]) + 2);
            $rank = (int)$this->position->current[1] - 1;
            if(PGN::square($file.$rank, true))
            {
                $this->position->scope->jumps[] = $file . $rank;
            }
        }
        catch (\InvalidArgumentException $e) {}

        try
        {
            $file = chr(ord($this->position->current[0]) + 2);
            $rank = (int)$this->position->current[1] + 1;
            if(PGN::square($file.$rank, true))
            {
                $this->position->scope->jumps[] = $file . $rank;
            }
        }
        catch (\InvalidArgumentException $e) {}

        try
        {
            $file = chr(ord($this->position->current[0]) + 1);
            $rank = (int)$this->position->current[1] + 2;
            if(PGN::square($file.$rank, true))
            {
                $this->position->scope->jumps[] = $file . $rank;
            }
        }
        catch (\InvalidArgumentException $e) {}

    }
}
