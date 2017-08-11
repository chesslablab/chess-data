<?php
namespace PGNChess\Piece;

use PGNChess\PGN;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Bishop;

/**
 * Queen class.
 *
 * This class uses a rook and a bishop.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Queen extends Slider
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
        parent::__construct($color, $square, PGN::PIECE_QUEEN);

        $this->rook = new Rook($color, $square, PGN::PIECE_ROOK);
        $this->bishop = new Bishop($color, $square, PGN::PIECE_BISHOP);
        
        $this->scope();
    }

    /**
     * Calculates the piece's scope.
     */
    protected function scope()
    {
        $this->position->scope = (object) array_merge(
            (array) $this->rook->getPosition()->scope,
            (array) $this->bishop->getPosition()->scope
        );
    }
}
