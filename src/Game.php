<?php
namespace PGNChess;

use DeepCopy\DeepCopy;
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
     * Plays a chess move on the board.
     *
     * @param stdClass $move
     * @return boolean
     */
    public function play($move)
    {
        $this->board = $this->board->replicate(); // workaround for deep clones to work

        $result = $this->board->play($move);

        $this->checked->{Symbol::WHITE} = false;
        $this->checked->{Symbol::BLACK} = false;
        $this->checked->{$this->board->getTurn()} = Analyze::check($this->board);

        $this->mated->{Symbol::WHITE} = false;
        $this->mated->{Symbol::BLACK} = false;
        $this->mated->{$this->board->getTurn()} = Analyze::mate($this->board);

        return $result;
    }
}
