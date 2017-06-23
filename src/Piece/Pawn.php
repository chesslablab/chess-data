<?php
namespace PGNChess\Piece;

use PGNChess\PGN;
use PGNChess\Piece\AbstractPiece;

class Pawn extends AbstractPiece
{
    private $ranks;

    public function __construct($color, $position)
    {
        parent::__construct($color, $position, PGN::PIECE_PAWN);

        switch ($this->color)
        {
            case PGN::COLOR_WHITE:
                $this->ranks = (object) [
                    'initial' => 2,
                    'next' => (int)$this->position->current[1] + 1,
                    'promotion' => 8
                ];
                break;

            case PGN::COLOR_BLACK:
                $this->ranks = (object) [
                    'initial' => 7,
                    'next' => (int)$this->position->current[1] - 1,
                    'promotion' => 1
                ];
                break;
        }

        $this->position->capture = [];
        $this->position->scope = (object)[
            'up' => []
        ];

        $this->scope();
    }

    protected function scope()
    {
        try // next rank
        {
            $file = $this->position->current[0];
            if (PGN::square($file.$this->ranks->next, true))
            {
                $this->position->scope->up[] = $file . $this->ranks->next;
            }
        }
        catch (\InvalidArgumentException $e) {}

        // two square advance

        if ($this->position->current[1] == 2 && $this->ranks->initial == 2)
        {
            $this->position->scope->up[] = $this->position->current[0] . ($this->ranks->initial + 2);
        }
        elseif ($this->position->current[1] == 7 && $this->ranks->initial == 7)
        {
            $this->position->scope->up[] = $this->position->current[0] . ($this->ranks->initial - 2);
        }

        try // capture square
        {
            $file = chr(ord($this->position->current[0]) - 1);
            if (PGN::square($file.$this->ranks->next, true))
            {
                $this->position->capture[] = $file . $this->ranks->next;
            }
        }
        catch (\InvalidArgumentException $e) {}

        try // capture square
        {
            $file = chr(ord($this->position->current[0]) + 1);
            if (PGN::square($file.$this->ranks->next, true))
            {
                $this->position->capture[] = $file . $this->ranks->next;
            }
        }
        catch (\InvalidArgumentException $e) {}

    }
}
