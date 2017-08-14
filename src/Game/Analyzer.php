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
     * Computes whether the current player is checked.
     *
     * @param PGNChess\Game\Board $board
     * @return boolean
     */
    public static function check($board)
    {
        $king = $board->getPiece($board->getTurn(), Symbol::KING);

        if (in_array(
            $king->getPosition()->current,
            $board->getControl()->attack->{$king->getOppositeColor()})
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Computes whether the current player is checkmated.
     *
     * @param PGNChess\Game\Board $board
     * @return boolean
     */
    public static function mate($board)
    {
        $moves = 0;

        if (self::check($board)) {

            $pieces = $board->getPiecesByColor($board->getTurn());

            foreach ($pieces as $piece) {

                foreach($piece->getLegalMoves() as $square) {

                    $deepCopy = new DeepCopy();
                    $that = $deepCopy->copy($board);

                    switch($piece->getIdentity()) {

                        case Symbol::PAWN:
                            if (in_array($square, $board->getSquares()->used->{$piece->getOppositeColor()})) {
                                $moves += (int) $that->play(
                                    Converter::toObject($board->getTurn(),
                                    $piece->getFile() . "x$square")
                                );
                            } else {
                                $moves += (int) $that->play(
                                    Converter::toObject($board->getTurn(),
                                    $square)
                                );
                            }
                            break;

                        default:
                            $moves += (int) $that->play(
                                Converter::toObject($board->getTurn(),
                                $piece->getIdentity() . $square)
                            );
                            if (in_array($square, $board->getSquares()->used->{$piece->getOppositeColor()})) {
                                $moves += (int) $that->play(
                                    Converter::toObject($board->getTurn(),
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
