<?php
namespace PGNChess\Piece;

use PGNChess\PGN;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Bishop;

/**
 * Class that represents a king.
 *
 * This class uses a rook and a bishop to keep things simple.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class King extends AbstractPiece
{
    /**
     * @var PGNChess\Piece\Rook
     */
    private $rook;

    /**
     * @var PGNChess\Piece\Bishop
     */
    private $bishop;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     */
    public function __construct($color, $square)
    {
        parent::__construct($color, $square, PGN::PIECE_KING);
        $this->rook = new Rook($color, $square, PGN::PIECE_ROOK);
        $this->bishop = new Bishop($color, $square, PGN::PIECE_BISHOP);
        $this->scope();
    }

    /**
     * Gets the castling rook associated to the king's next move.
     *
     * @param array $pieces
     *
     * @return null|PGNChess\Piece\Rook
     */
    private function getCastlingRook(array $pieces)
    {
        foreach ($pieces as $piece)
        {
            if (
                $piece->getIdentity() === PGN::PIECE_ROOK &&
                $piece->getPosition()->current === PGN::castling($this->getColor())->{PGN::PIECE_ROOK}->{$this->getNextMove()->type}->position->current
            )
            {
                return $piece;
            }
        }
        return null;
    }

    /**
     * Calculates the king's scope.
     */
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

    public function getLegalMoves()
    {
        $moves = [];
        switch ($this->getNextMove()->type)
        {
            // TODO
            case PGN::MOVE_TYPE_KING:
                break;

            // TODO
            case PGN::MOVE_TYPE_KING_CAPTURES:
                break;

            case PGN::MOVE_TYPE_KING_CASTLING_SHORT:
                $castlingShort = PGN::castling($this->getColor())->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT};
                if (
                    !in_array($castlingShort->freeSquares->f, $this->squares->used->{$this->getColor()}) &&
                    !in_array($castlingShort->freeSquares->f, $this->squares->used->{$this->getOppositeColor()}) &&
                    !in_array($castlingShort->freeSquares->g, $this->squares->used->{$this->getColor()}) &&
                    !in_array($castlingShort->freeSquares->g, $this->squares->used->{$this->getOppositeColor()})
                )
                {
                    // TODO fix this, find an alternative to get the castling rook...
                    $moves[] = !empty($this->getCastlingRook($piece)) ? $this->getNextMove()->position->next : false;
                }
                break;

            case PGN::MOVE_TYPE_KING_CASTLING_LONG:
                $castlingLong = PGN::castling($this->getColor())->{PGN::PIECE_KING}->{PGN::CASTLING_LONG};
                if (
                    !in_array($castlingLong->freeSquares->b, $this->squares->used->{$this->getColor()}) &&
                    !in_array($castlingLong->freeSquares->b, $this->squares->used->{$this->getOppositeColor()}) &&
                    !in_array($castlingLong->freeSquares->c, $this->squares->used->{$this->getColor()}) &&
                    !in_array($castlingLong->freeSquares->c, $this->squares->used->{$this->getOppositeColor()}) &&
                    !in_array($castlingLong->freeSquares->d, $this->squares->used->{$this->getColor()}) &&
                    !in_array($castlingLong->freeSquares->d, $this->squares->used->{$this->getOppositeColor()})
                )
                {
                    $moves[] = !empty($this->getCastlingRook($piece)) ? $this->getNextMove()->position->next : false;
                }
                break;
        }
        return $moves;
    }
}
