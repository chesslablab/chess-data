<?php
namespace PGNChess\Piece;

use PGNChess\PGN;
use PGNChess\Piece\AbstractPiece;

class Knight extends AbstractPiece
{
    public function __construct($color, $position)
    {
        parent::__construct($color, $position, PGN::PIECE_KNIGHT);
    }

    protected function scope()
    {
    }
}
