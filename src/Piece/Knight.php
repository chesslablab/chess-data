<?php
namespace PGNChess\Piece;

use PGNChess\PGN;
use PGNChess\Piece\AbstractPiece;

class Knight extends AbstractPiece
{
    public function __construct($color, $square)
    {
        parent::__construct($color, $square, PGN::PIECE_KNIGHT);
        $this->position->scope = (object)[
            'jumps' => []
        ];
        $this->scope();
    }

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
