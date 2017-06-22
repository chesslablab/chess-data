<?php
namespace PGNChess\Piece;

use PGNChess\PGN;

abstract class AbstractPiece
{
    protected $color;

    protected $position;

    protected $identity;

    public function __construct($color, $position, $identity)
    {
        PGN::color($color) ? $this->color = $color : false;

        $this->position = (object) [
            'current' => PGN::square($position) ? $position : null,
            'scope' => []
        ];

        $this->identity = $identity;
    }

    abstract protected function scope();

    public function getColor()
    {
        return $this->color;
    }

    public function getOppositeColor()
    {
        if ($this->color == PGN::COLOR_WHITE)
        {
            return PGN::COLOR_BLACK;
        }
        else
        {
            return PGN::COLOR_WHITE;
        }
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getIdentity()
    {
        return $this->identity;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

}
