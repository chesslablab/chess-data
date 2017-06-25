<?php
namespace PGNChess\Piece;

use PGNChess\Piece\Piece;
use PGNChess\PGN;

/**
 * Class that represents a chess piece.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
abstract class Slider extends AbstractPiece
{
    public function __construct($color, $square, $identity)
    {
        parent::__construct($color, $square, $identity);
    }

    /**
     * Gets the legal moves that can be performed on the board by slider pieces.
     *
     * @return array The legal moves that the slider piece (BRQ) can perform.
     */
    public function getLegalMoves()
    {
        $moves = [];
        $scope = $this->getPosition()->scope;
        foreach ($scope as $walk)
        {
            foreach ($walk as $square)
            {
                if (
                    !in_array($square, $this->squares->used->{$this->getColor()}) &&
                    !in_array($square, $this->squares->used->{$this->getOppositeColor()})
                )
                {
                    $moves[] = $square;
                }
                elseif (in_array($square, $this->squares->used->{$this->getOppositeColor()}))
                {
                    $moves[] = $square;
                    break 1;
                }
                elseif (in_array($square, $this->squares->used->{$this->getColor()}))
                {
                    break 1;
                }
            }
        }
        return $moves;
    }
}
