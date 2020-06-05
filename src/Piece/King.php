<?php
namespace PGNChess\Piece;

use PGNChess\Square\Castling;
use PGNChess\PGN\Move;
use PGNChess\PGN\Symbol;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\Type\RookType;

/**
 * King class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class King extends AbstractPiece
{
    /**
     * @var \PGNChess\Piece\Rook
     */
    private $rook;

    /**
     * @var \PGNChess\Piece\Bishop
     */
    private $bishop;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     */
    public function __construct(string $color, string $square)
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
     * @return mixed \PGNChess\Piece\Rook|null
     */
    public function getCastlingRook(array $pieces)
    {
        foreach ($pieces as $piece) {
            if (
                $piece->getIdentity() === Symbol::ROOK &&
                $piece->getPosition() ===
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
    protected function scope(): void
    {
        $scope =  array_merge(
            (array) $this->rook->getScope(),
            (array) $this->bishop->getScope()
        );

        foreach($scope as $key => $val) {
            $scope[$key] = !empty($val[0]) ? $val[0] : null;
        }

        $this->scope = (object) array_filter(array_unique($scope));
    }

    /**
     * Gets the king's legal moves.
     *
     * @return array
     */
    public function getLegalMoves(): array
    {
        $movesKing = array_values(
            array_intersect(
                array_values((array)$this->scope),
                $this->getBoardStatus()->squares->free
        ));

        $movesKingCaptures = array_values(
            array_intersect(
                array_values((array)$this->scope),
                array_merge($this->getBoardStatus()->squares->used->{$this->getOppositeColor()})
        ));

        $castlingShort = Castling::info($this->getColor())->{Symbol::KING}->{Symbol::CASTLING_SHORT};
        $castlingLong = Castling::info($this->getColor())->{Symbol::KING}->{Symbol::CASTLING_LONG};

        if (
            !$this->getBoardStatus()->castling->{$this->getColor()}->castled &&
            $this->getBoardStatus()->castling->{$this->getColor()}->{Symbol::CASTLING_SHORT} &&
            in_array($castlingShort->squares->f, $this->getBoardStatus()->squares->free) &&
            in_array($castlingShort->squares->g, $this->getBoardStatus()->squares->free) &&
            !in_array($castlingShort->squares->f, $this->getBoardControl()->space->{$this->getOppositeColor()}) &&
            !in_array($castlingShort->squares->g, $this->getBoardControl()->space->{$this->getOppositeColor()})
        ) {
            $movesCastlingShort = [$castlingShort->position->next];
        }
        else {
            $movesCastlingShort = [];
        }

        if (
            !$this->getBoardStatus()->castling->{$this->getColor()}->castled &&
            $this->getBoardStatus()->castling->{$this->getColor()}->{Symbol::CASTLING_LONG} &&
            in_array($castlingLong->squares->b, $this->getBoardStatus()->squares->free) &&
            in_array($castlingLong->squares->c, $this->getBoardStatus()->squares->free) &&
            in_array($castlingLong->squares->d, $this->getBoardStatus()->squares->free) &&
            !in_array($castlingLong->squares->b, $this->getBoardControl()->space->{$this->getOppositeColor()}) &&
            !in_array($castlingLong->squares->c, $this->getBoardControl()->space->{$this->getOppositeColor()}) &&
            !in_array($castlingLong->squares->d, $this->getBoardControl()->space->{$this->getOppositeColor()})
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
