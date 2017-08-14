<?php
namespace PGNChess\Game;

use DeepCopy\DeepCopy;
use PGNChess\Game\Board;
use PGNChess\PGN\Converter;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validator;

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
     * @return PGNChess\Game\Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Shows whether the given player is checked.
     *
     * @param string $color
     * @return boolean
     */
    public function isChecked($color)
    {
        return $this->checked->{Validator::color($color)};
    }

    /**
     * Shows whether the given player is mated.
     *
     * @param string $color
     * @return boolean
     */
    public function isMated($color)
    {
        return $this->mated->{Validator::color($color)};
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

        $this->checked->{$this->board->getTurn()} = Analyzer::check($this->board);
        $this->mated->{$this->board->getTurn()} = Analyzer::mate($this->board);

        return $result;
    }
}
