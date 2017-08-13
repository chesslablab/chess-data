<?php
namespace PGNChess\PGN;

use PGNChess\Castling;
use PGNChess\PGN\Notation;
use PGNChess\PGN\Validator;

/**
 * Converter class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Converter
{
    /**
     * Converts a PGN move into a stdClass object for further processing.
     *
     * @param string $color
     * @param string $pgn
     * @return stdClass
     * @throws \InvalidArgumentException
     */
    static public function toObject($color, $pgn)
    {
        Validator::color($color);

        $isCheck = substr($pgn, -1) === '+' || substr($pgn, -1) === '#';

        switch(true) {
            case preg_match('/^' . Notation::MOVE_TYPE_KING . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Notation::MOVE_TYPE_KING,
                    'color' => $color,
                    'identity' => Notation::PIECE_KING,
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, -2)
                ]];
                break;

            case preg_match('/^' . Notation::MOVE_TYPE_KING_CASTLING_SHORT . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Notation::MOVE_TYPE_KING_CASTLING_SHORT,
                    'color' => $color,
                    'identity' => Notation::PIECE_KING,
                    'position' => Castling::info($color)->{Symbol::PIECE_KING}->{PGN::CASTLING_SHORT}->position
                ];
                break;

            case preg_match('/^' . Notation::MOVE_TYPE_KING_CASTLING_LONG . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Notation::MOVE_TYPE_KING_CASTLING_LONG,
                    'color' => $color,
                    'identity' => Notation::PIECE_KING,
                    'position' => Castling::info($color)->{Symbol::PIECE_KING}->{PGN::CASTLING_LONG}->position
                ];
                break;

            case preg_match('/^' . Notation::MOVE_TYPE_KING_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Notation::MOVE_TYPE_KING_CAPTURES,
                    'color' => $color,
                    'identity' => Notation::PIECE_KING,
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, -2)
                ]];
                break;

            case preg_match('/^' . Notation::MOVE_TYPE_PIECE . '$/', $pgn):
                if (!$isCheck) {
                    $currentPosition = mb_substr(mb_substr($pgn, 0, -2), 1);
                    $nextPosition = mb_substr($pgn, -2);
                } else {
                    $currentPosition = mb_substr(mb_substr($pgn, 0, -3), 1);
                    $nextPosition = mb_substr(mb_substr($pgn, 0, -1), -2);
                }
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Notation::MOVE_TYPE_PIECE,
                    'color' => $color,
                    'identity' => mb_substr($pgn, 0, 1),
                    'position' => (object) [
                        'current' => $currentPosition,
                        'next' => $nextPosition
                ]];
                break;

            case preg_match('/^' . Notation::MOVE_TYPE_PIECE_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Notation::MOVE_TYPE_PIECE_CAPTURES,
                    'color' => $color,
                    'identity' => mb_substr($pgn, 0, 1),
                    'position' => (object) [
                        'current' => !$isCheck ? mb_substr(mb_substr($pgn, 0, -3), 1) : mb_substr(mb_substr($pgn, 0, -4), 1),
                        'next' => !$isCheck ? mb_substr($pgn, -2) : mb_substr($pgn, -3, -1)
                ]];
                break;

            case preg_match('/^' . Notation::MOVE_TYPE_KNIGHT . '$/', $pgn):
                if (!$isCheck) {
                    $currentPosition = mb_substr(mb_substr($pgn, 0, -2), 1);
                    $nextPosition = mb_substr($pgn, -2);
                } else {
                    $currentPosition = mb_substr(mb_substr($pgn, 0, -3), 1);
                    $nextPosition = mb_substr(mb_substr($pgn, 0, -1), -2);
                }
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Notation::MOVE_TYPE_KNIGHT,
                    'color' => $color,
                    'identity' => Notation::PIECE_KNIGHT,
                    'position' => (object) [
                        'current' => $currentPosition,
                        'next' => $nextPosition
                ]];
                break;

            case preg_match('/^' . Notation::MOVE_TYPE_KNIGHT_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Notation::MOVE_TYPE_KNIGHT_CAPTURES,
                    'color' => $color,
                    'identity' => Notation::PIECE_KNIGHT,
                    'position' => (object) [
                        'current' => !$isCheck ? mb_substr(mb_substr($pgn, 0, -3), 1) : mb_substr(mb_substr($pgn, 0, -4), 1),
                        'next' => !$isCheck ? mb_substr($pgn, -2) : mb_substr($pgn, -3, -1)
                ]];
                break;

            case preg_match('/^' . Notation::MOVE_TYPE_PAWN . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Notation::MOVE_TYPE_PAWN,
                    'color' => $color,
                    'identity' => Notation::PIECE_PAWN,
                    'position' => (object) [
                        'current' => mb_substr($pgn, 0, 1),
                        'next' => !$isCheck ? $pgn : mb_substr($pgn, 0, -1)
                ]];
                break;

            case preg_match('/^' . Notation::MOVE_TYPE_PAWN_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Notation::MOVE_TYPE_PAWN_CAPTURES,
                    'color' => $color,
                    'identity' => Notation::PIECE_PAWN,
                    'position' => (object) [
                        'current' => mb_substr($pgn, 0, 1),
                        'next' => !$isCheck ? mb_substr($pgn, -2) : mb_substr($pgn, -3, -1)
                ]];
                break;

            case preg_match('/^' . Notation::MOVE_TYPE_PAWN_PROMOTES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Notation::MOVE_TYPE_PAWN_PROMOTES,
                    'color' => $color,
                    'identity' => Notation::PIECE_PAWN,
                    'newIdentity' => !$isCheck ? mb_substr($pgn, -1) : mb_substr($pgn, -2, -1),
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, 0, 2)
                ]];
                break;

            case preg_match('/^' . Notation::MOVE_TYPE_PAWN_CAPTURES_AND_PROMOTES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Notation::MOVE_TYPE_PAWN_CAPTURES_AND_PROMOTES,
                    'color' => $color,
                    'identity' => Notation::PIECE_PAWN,
                    'newIdentity' => !$isCheck ? mb_substr($pgn, -1) : mb_substr($pgn, -2, -1),
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, 2, 2)
                ]];
                break;

            default:
                throw new \InvalidArgumentException("This move is not valid: $pgn.");
                break;
        }
    }
}
