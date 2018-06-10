<?php

namespace PGNChess;

use PGNChess\PGN\Convert;
use PGNChess\PGN\Validate;

/**
 * Game class.
 *
 * This is a wrapper of the Board class that makes available to the outside world
 * a few methods of it only. Additionally, it outputs the data managed internally
 * in a user-friendly way.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Game
{
    /**
     * Chess board.
     *
     * @var Board
     */
    private $board;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->board = new Board();
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
            'castling' => $this->board->getCastling(),
        ];
    }

    /**
     * Gets the board's history.
     *
     * @return array
     */
    public function history()
    {
        $userFriendlyHistory = [];

        $boardHistory = $this->board->getHistory();

        foreach ($boardHistory as $entry) {
            $userFriendlyHistory[] = (object) [
                'pgn' => $entry->move->pgn,
                'color' => $entry->move->color,
                'identity' => $entry->move->identity,
                'position' => $entry->position,
                'isCapture' => $entry->move->isCapture,
                'isCheck' => $entry->move->isCheck,
            ];
        }

        return $userFriendlyHistory;
    }

    /**
     * Gets the pieces captured by both players.
     *
     * @return array
     */
    public function captures()
    {
        return $this->board->getCaptures();
    }

    /**
     * Gets an array of pieces by color.
     *
     * @param string $color
     * @return array
     */
    public function pieces($color)
    {
        $result = [];

        $pieces = $this->board->getPiecesByColor(Validate::color($color));

        foreach ($pieces as $piece) {
            $result[] = (object) [
                'identity' => $piece->getIdentity(),
                'position' => $piece->getPosition(),
                'moves' => $piece->getLegalMoves(),
            ];
        }

        return $result;
    }

    /**
     * Gets a piece by its position on the board.
     *
     * @param string $square
     * @return mixed null|\stdClass
     */
    public function piece($square)
    {
        $piece = $this->board->getPieceByPosition(Validate::square($square));

        if ($piece === null) {
            return null;
        } else {
            return (object) [
                'color' => $piece->getColor(),
                'identity' => $piece->getIdentity(),
                'position' => $piece->getPosition(),
                'moves' => $piece->getLegalMoves(),
            ];
        }
    }

    /**
     * Calculates whether the current player is checked.
     *
     * @return bool
     */
    public function isCheck()
    {
        return $this->board->isCheck();
    }

    /**
     * Calculates whether the current player is mated.
     *
     * @return bool
     */
    public function isMate()
    {
        return $this->board->isMate();
    }

    /**
     * Plays a chess move on the board.
     *
     * @param string $color
     * @param string $pgn
     * @return bool
     */
    public function play($color, $pgn)
    {
        return $this->board->play(Convert::toObject($color, $pgn));
    }
}
