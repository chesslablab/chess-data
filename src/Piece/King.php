<?php
namespace PGNChess\Piece;

use PGNChess\PGN;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Bishop;

/**
 * King class.
 *
 * Rather than implementing everything from scratch, this class uses a rook and
 * a bishop.
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
     * @var stdClass
     */
    private $castling;

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

        $this->castling = (object) [
            'isCastled' => false,
            PGN::CASTLING_SHORT => (object) [
                'canCastle' => true
            ],
            PGN::CASTLING_LONG => (object) [
                'canCastle' => true
        ]];

        $this->scope();
    }

    public function getCastling()
    {
        return $this->castling;
    }

    /**
     * Gets the castling rook associated to the king's next move.
     *
     * @param array $piece
     * @return null|PGNChess\Piece\Rook
     */
    public function getCastlingRook(array $pieces)
    {
        foreach ($pieces as $piece) {
            if (
                $piece->getIdentity() === PGN::PIECE_ROOK &&
                $piece->getPosition()->current === PGN::castling($this->getColor())->{PGN::PIECE_ROOK}->{$this->getMove()->pgn}->position->current
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
        $moves = [];
        switch ($this->getMove()->type) {

            case PGN::MOVE_TYPE_KING:
                $moves = array_values(
                    array_intersect(
                        array_values((array)$this->position->scope),
                        self::$squares->free
                ));
                break;

            case PGN::MOVE_TYPE_KING_CAPTURES:
                $moves = array_values(
                    array_intersect(
                        array_values((array)$this->position->scope),
                        array_merge(self::$squares->used->{$this->getOppositeColor()})
                ));
                break;

            case PGN::MOVE_TYPE_KING_CASTLING_SHORT:
                $castlingShort = PGN::castling($this->getColor())->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT};
                if (
                    in_array($castlingShort->freeSquares->f, self::$squares->free) &&
                    in_array($castlingShort->freeSquares->g, self::$squares->free)
                ) {
                    $moves[] = $this->getMove()->position->next;
                }
                break;

            case PGN::MOVE_TYPE_KING_CASTLING_LONG:
                $castlingLong = PGN::castling($this->getColor())->{PGN::PIECE_KING}->{PGN::CASTLING_LONG};
                if (
                    in_array($castlingLong->freeSquares->b, self::$squares->free) &&
                    in_array($castlingLong->freeSquares->c, self::$squares->free) &&
                    in_array($castlingLong->freeSquares->d, self::$squares->free)
                ) {
                    $moves[] = $this->getMove()->position->next;
                }
                break;
        }

        return $moves;
    }

    /**
     * Sets the king as castled.
     */
    public function setIsCastled()
    {
        $this->castling->isCastled = true;
        $this->forbidCastling();

        return $this;
    }

    /**
     * Forbids the king to castle.
     *
     * @param string $type
     * @return PGNChess\Piece\King
     */
    public function forbidCastling($type=null)
    {
        if (isset($type)) {
            $this->castling->{$type}->canCastle = false;
        } else {
            $this->castling->{PGN::CASTLING_SHORT}->canCastle = false;
            $this->castling->{PGN::CASTLING_LONG}->canCastle = false;
        }

        return $this;
    }

    /**
     * Updates the king's castling status.
     *
     * @return PGNChess\Piece\King
     */
    public function updateCastling()
    {
        if (!$this->castling->isCastled) {
            $this->castling->{PGN::CASTLING_SHORT}->canCastle = false;
            $this->castling->{PGN::CASTLING_LONG}->canCastle = false;
        }

        return $this;
    }
}
