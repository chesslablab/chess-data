<?php
namespace PGNChess\Piece;

use PGNChess\PGN;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Bishop;

class King extends AbstractPiece
{
    private $rook;

    private $bishop;

    public function __construct($color, $square)
    {
        parent::__construct($color, $square, PGN::PIECE_KING);
        $this->rook = new Rook($color, $square, PGN::PIECE_ROOK);
        $this->bishop = new Bishop($color, $square, PGN::PIECE_BISHOP);
        $this->scope();
    }

    protected function scope()
    {
        $scope =  array_merge(
            (array) $this->rook->getPosition()->scope,
            (array) $this->bishop->getPosition()->scope
        );
        foreach($scope as $key => $val)
        {
            $scope[$key] = !empty($val[0]) ? $val[0] : null;
        }
        $this->position->scope = (object) $scope;
    }
}
