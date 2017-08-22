<?php
namespace PGNChess;

use PGNChess\Board;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validate;

/**
 * Game class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Game
{
    /**
     * Chess board.
     *
     * @var PGNChess\Game\Board
     */
    private $board;

    /**
     * Determines whether the current player is checked.
     *
     * @var stdClass
     */
    private $checked;

    /**
     * Determines whether the current player is mated.
     *
     * @var stdClass
     */
    private $mated;

    /**
     * Board's status.
     *
     * @var stdClass
     */
    private $status;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->board = new Board;

        $this->checked = (object) [
            Symbol::WHITE => false,
            Symbol::BLACK => false
        ];

        $this->mated = (object) [
            Symbol::WHITE => false,
            Symbol::BLACK => false
        ];
    }

    /**
     * Gets the chess board.
     *
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Sets the chess board.
     *
     * @param Board $board
     */
    public function setBoard(Board $board)
    {
        $this->board = $board;

        $this->checked->{Symbol::WHITE} = false;
        $this->checked->{Symbol::BLACK} = false;

        $this->mated->{Symbol::WHITE} = false;
        $this->mated->{Symbol::BLACK} = false;
    }

    /**
     * Shows whether the given player is checked.
     *
     * @param string $color
     * @return boolean
     */
    public function isChecked($color)
    {
        return $this->checked->{Validate::color($color)};
    }

    /**
     * Shows whether the given player is mated.
     *
     * @param string $color
     * @return boolean
     */
    public function isMated($color)
    {
        return $this->mated->{Validate::color($color)};
    }

    /**
     * Gets the current board's status.
     *
     * @return stdClass
     */
    public function status()
    {
        return (object) [
            'turn' => $this->board->getTurn(),
            'squares' => $this->board->getSquares(),
            'control' => $this->board->getControl(),
            'castling' => $this->board->getCastling(),
            'previousMove' => $this->board->getPreviousMove()
        ];
    }

    /**
     * Gets an array of pieces by color.
     *
     * @param string $color
     * @return array
     */
    public function getPiecesByColor($color)
    {
        $result = [];

        $pieces = $this->board->getPiecesByColor(Validate::color($color));

        foreach ($pieces as $piece) {
            $result[] = (object) [
                'identity' => $piece->getIdentity(),
                'position' => $piece->getPosition()->current,
                'moves' => $piece->getLegalMoves()
            ];
        }

        return $result;
    }

    /**
     * Gets a piece by its position on the board.
     *
     * @param $square
     * @return stdClass
     */
    public function getPieceByPosition($square)
    {
        $piece = $this->board->getPieceByPosition(Validate::square($square));

        return (object) [
            'color' => $piece->getColor(),
            'identity' => $piece->getIdentity(),
            'position' => $piece->getPosition()->current,
            'moves' => $piece->getLegalMoves()
        ];
    }

    /**
     * Plays a chess move on the board.
     *
     * @param stdClass $move
     * @return boolean
     */
    public function play($move)
    {
        $this->checked->{Symbol::WHITE} = false;
        $this->checked->{Symbol::BLACK} = false;
        
        $result = $this->board->play($move);

        if ($this->checked->{$this->board->getTurn()} = $this->board->isCheck()) {
            // $this->mated->{$this->board->getTurn()} = $this->board->isMate();
        }

        return $result;
    }
}
