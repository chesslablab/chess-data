<?php
namespace PGNChess;

use PGNChess\Board;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validate;

/**
 * Game class.
 * 
 * This is a wrapper of the Board class that make available to the outside world
 * a few methods of it only. Additionally, it outputs the data managed internally
 * in a user-friendly way.
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
     * @var \PGNChess\Game\Board
     */
    private $board;
    
    /**
     * Board's status.
     *
     * @var \stdClass
     */
    private $status;
    
    /**
     * History.
     * 
     * @var array 
     */
    private $history;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->board = new Board;
    }
  
    /**
     * Calculates whether the current player is checked.
     *
     * @return boolean
     */
    public function isCheck()
    {
       return $this->board->isCheck();
    }

    /**
     * Calculates whether the current player is mated.
     *
     * @return boolean
     */
    public function isMate()
    {
        return $this->board->isMate();
    }

    /**
     * Gets the board's status.
     *
     * @return \stdClass
     */
    public function status()
    {
        return (object) [
            'turn' => $this->board->getTurn(),
            'squares' => $this->board->getSquares(),
            'control' => $this->board->getControl(),
            'castling' => $this->board->getCastling()
        ];
    }
    
    /**
     * Gets the board's history.
     * 
     * @return array
     */
    public function history()
    {
        return $this->board->getHistory();
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
                'position' => $piece->getPosition(),
                'moves' => $piece->getLegalMoves()
            ];
        }

        return $result;
    }

    /**
     * Gets a piece by its position on the board.
     *
     * @param string $square
     * @return \stdClass
     */
    public function getPieceByPosition($square)
    {
        $piece = $this->board->getPieceByPosition(Validate::square($square));

        return (object) [
            'color' => $piece->getColor(),
            'identity' => $piece->getIdentity(),
            'position' => $piece->getPosition(),
            'moves' => $piece->getLegalMoves()
        ];
    }

    /**
     * Plays a chess move on the board.
     *
     * @param \stdClass $move
     * @return boolean
     */
    public function play($move)
    {
        return $this->board->play($move);
    }
}
