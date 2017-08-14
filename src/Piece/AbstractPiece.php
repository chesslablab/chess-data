<?php
namespace PGNChess\Piece;

use PGNChess\Piece\Piece;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validator;

/**
 * Class that represents a chess piece.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
abstract class AbstractPiece implements Piece
{
    /**
     * The piece's color in PGN format.
     *
     * @var string
     */
    protected $color;

    /**
     * The piece's position on the board.
     *
     * @var stdClass
     */
    protected $position;

    /**
     * The piece's identity in PGN format.
     *
     * @var string
     */
    protected $identity;

    /**
     * The piece's next move to be performed on the board.
     *
     * @var stdClass
     */
    protected $move;

    /**
     * The legal moves that the piece can carry out.
     *
     * @var array
     */
    protected $legalMoves;

    /**
     * Chess board status accessible by all pieces.
     *
     * @var stdClass
     */
    protected static $boardStatus;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     * @param string $identity
     */
    public function __construct($color, $square, $identity)
    {
        $this->color = Validator::color($color);

        $this->position = (object) [
            'current' => Validator::square($square),
            'scope' => []
        ];

        $this->identity = $identity;
    }

    /**
     * Calculates the piece's scope.
     */
    abstract protected function scope();

    /**
     * Gets the piece's color.
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Gets the piece's opposite color.
     *
     * @return string
     */
    public function getOppositeColor()
    {
        if ($this->color == Symbol::WHITE) {
            return Symbol::BLACK;
        } else {
            return Symbol::WHITE;
        }
    }

    /**
     * Gets the piece's position on the board.
     *
     * @return stdClass
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Gets the piece's identity.
     *
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * Gets the piece's next move.
     *
     * @return stdClass
     */
    public function getMove()
    {
        return $this->move;
    }

    /**
     * Gets the legal moves that a piece can perform on the board.
     *
     * @return array The legal moves that the piece can perform.
     */
    abstract public function getLegalMoves();

    /**
     * Sets the piece's position.
     *
     * @param stdClass $position
     */
    public function setPosition(\stdClass $position)
    {
        $this->position->current === $this->move->position->next
            ? $this->position = $position
            : $this->position = null;

        return $this;
    }

    /**
     * Sets the piece's next move.
     *
     * @param stdClass $move
     */
    public function setMove(\stdClass $move)
    {
        $this->move = $move;
    }

    /**
     * Sets the board status.
     *
     * @param stdClass $boardStatus
     */
    public static function setBoardStatus(\stdClass $boardStatus)
    {
        self::$boardStatus = $boardStatus;
    }

    /**
     * Checks whether or not the piece can be moved on the board.
     *
     * @return boolean true if the piece can be moved; otherwise false
     */
    public function isMovable()
    {
        if (isset($this->move)) {
            return in_array($this->move->position->next, $this->getLegalMoves());
        } else {
            return false;
        }
    }
}
