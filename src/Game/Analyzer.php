<?php
namespace PGNChess\Game;

use DeepCopy\DeepCopy;
use PGNChess\Game\Board;
use PGNChess\PGN\Converter;
use PGNChess\PGN\Symbol;

/**
 * Analyzer class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Analyzer
{
    /**
     * Determines whether the current player is checked.
     *
     * @param PGNChess\Game\Board $board
     * @return boolean
     */
    public static function check($board)
    {
        $king = $board->getPiece($board->getStatus()->turn, Symbol::KING);

        if (in_array(
            $king->getPosition()->current,
            $board->getStatus()->control->attack->{$king->getOppositeColor()})
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determines whether the current player is checkmated.
     *
     * @param PGNChess\Game\Board $board
     * @return boolean
     */
    public static function mate($board)
    {
        $moves = 0;

        if (self::check($board)) {

            $pieces = $board->getPiecesByColor($board->getStatus()->turn);

            foreach ($pieces as $piece) {

                foreach($piece->getLegalMoves() as $square) {

                    $deepCopy = new DeepCopy();
                    $that = $deepCopy->copy($board);

                    switch($piece->getIdentity()) {

                        case Symbol::PAWN:
                            if (in_array($square, $board->getStatus()->squares->used->{$piece->getOppositeColor()})) {
                                $moves += (int) $that->play(
                                    Converter::toObject($board->getStatus()->turn,
                                    $piece->getFile() . "x$square")
                                );
                            } else {
                                $moves += (int) $that->play(
                                    Converter::toObject($board->getStatus()->turn,
                                    $square)
                                );
                            }
                            break;

                        default:
                            $moves += (int) $that->play(
                                Converter::toObject($board->getStatus()->turn,
                                $piece->getIdentity() . $square)
                            );
                            if (in_array($square, $board->getStatus()->squares->used->{$piece->getOppositeColor()})) {
                                $moves += (int) $that->play(
                                    Converter::toObject($board->getStatus()->turn,
                                    $piece->getIdentity() . "x$square")
                                );
                            }
                            break;
                    }
                }
            }

            if ($moves === 0) {
                return true;
            } else {
                return false;
            }
        }
    }
}
