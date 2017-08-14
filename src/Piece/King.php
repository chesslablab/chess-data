<?php
namespace PGNChess\Piece;

use PGNChess\Castling;
use PGNChess\PGN\Move;
use PGNChess\PGN\Symbol;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Bishop;
use PGNChess\Type\RookType;

/**
 * King class.
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
        parent::__construct($color, $square, Symbol::KING);

        $this->rook = new Rook($color, $square, RookType::FAKED);
        $this->bishop = new Bishop($color, $square);

        $this->scope();
    }

    /**
     * Gets the king's castling rook.
     *
     * @param array $pieces
     * @return null|PGNChess\Piece\Rook
     */
    public function getCastlingRook(array $pieces)
    {
        foreach ($pieces as $piece) {
            if (
                $piece->getIdentity() === Symbol::ROOK &&
                $piece->getPosition()->current ===
                Castling::info($this->getColor())->{Symbol::ROOK}->{$this->getMove()->pgn}->position->current
            ) {
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

        foreach($scope as $key => $val) {
            $scope[$key] = !empty($val[0]) ? $val[0] : null;
        }

        $this->position->scope = (object) array_filter(array_unique($scope));
    }

    /**
     * Gets the king's legal moves.
     *
     * @return array
     */
    public function getLegalMoves()
    {
        $movesKing = array_values(
            array_intersect(
                array_values((array)$this->position->scope),
                self::$boardStatus->squares->free
        ));

        $movesKingCaptures = array_values(
            array_intersect(
                array_values((array)$this->position->scope),
                array_merge(self::$boardStatus->squares->used->{$this->getOppositeColor()})
        ));

        $castlingShort = Castling::info($this->getColor())->{Symbol::KING}->{Symbol::CASTLING_SHORT};
        $castlingLong = Castling::info($this->getColor())->{Symbol::KING}->{Symbol::CASTLING_LONG};

        if (
            !self::$boardStatus->castling->{$this->getColor()}->castled &&
            in_array($castlingShort->freeSquares->f, self::$boardStatus->squares->free) &&
            in_array($castlingShort->freeSquares->g, self::$boardStatus->squares->free)
        ) {
            $movesCastlingShort = [$castlingShort->position->next];
        }
        else {
            $movesCastlingShort = [];
        }

        if (
            !self::$boardStatus->castling->{$this->getColor()}->castled &&
            in_array($castlingLong->freeSquares->b, self::$boardStatus->squares->free) &&
            in_array($castlingLong->freeSquares->c, self::$boardStatus->squares->free) &&
            in_array($castlingLong->freeSquares->d, self::$boardStatus->squares->free)
        ) {
            $movesCastlingLong = [$castlingLong->position->next];
        }
        else {
            $movesCastlingLong = [];
        }

        return array_unique(
            array_values(
                array_merge($movesKing, $movesKingCaptures, $movesCastlingShort, $movesCastlingLong)
        ));
    }
}
