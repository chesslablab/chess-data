<?php
namespace PGNChess\Piece;

use PGNChess\Piece\Piece;
use PGNChess\PGN;

abstract class AbstractPiece implements Piece
{
    protected $color;

    protected $position;

    protected $identity;

    protected $nextMove;

    public function __construct($color, $square, $identity)
    {
        PGN::color($color) ? $this->color = $color : false;

        $this->position = (object) [
            'current' => PGN::square($square) ? $square : null,
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

    public function getNextMove()
    {
        return $this->nextMove;
    }

    public function setPosition(\stdClass $position)
    {
        $this->position = $position;
    }

    public function setNextMove(\stdClass $move)
    {
        $this->nextMove = $move;
    }

}
