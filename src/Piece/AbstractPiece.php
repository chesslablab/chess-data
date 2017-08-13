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
     * The piece's color in PGN format; for exmaple 'w' or 'b'.
     *
     * @var string
     */
    protected $color;

    /**
     * The piece's position on a board.
     *
     *      $forExample = (object) [
     *          'current' => 'b',
     *          'next' => 'b7'
     *      ];
     *
     * @var stdClass
     */
    protected $position;

    /**
     * The piece's identity in PGN format; for example 'K', 'Q' or 'N'.
     *
     * @var string
     */
    protected $identity;

    /**
     * The piece's next move to be performed on the board. This is the processable,
     * objectized counterpart of a valid PGN move.
     *
     *      $forExample = (object) [
     *          'type' => PGN::MOVE_TYPE_PIECE,
     *          'color' => 'w',
     *          'identity' => PGN::BISHOP,
     *          'position' => (object) [
     *              'current' => null,
     *              'next' =>'g5'
     *          ]
     *      ];
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

    protected static $squares;

    protected static $previousMove;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     * @param string $identity
     */
    public function __construct($color, $square, $identity)
    {
        Validator::color($color) ? $this->color = $color : false;
        $this->position = (object) [
            'current' => Validator::square($square) ? $square : null,
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
     * This is flat information about the chess board, which is accessible by all pieces
     * for computing their legal moves, etc.
     *
     * @param stdClass $squares
     */
    public static function setSquares(\stdClass $squares)
    {
        self::$squares = $squares;
    }

    public static function setPreviousMove(\stdClass $previousMove)
    {
        self::$previousMove = $previousMove;
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
