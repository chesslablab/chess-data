<?php
namespace PGNChess;

use PGNChess\PGN;
use PGNChess\Squares;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Piece;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;

/**
 * Class that represents a chess board.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Board extends \SplObjectStorage
{
    /**
     * @var stdClass
     */
    private $status;

    /**
     * Constructor.
     *
     * @param null|array $pieces
     */
    public function __construct(array $pieces=null)
    {
        if (empty($pieces)) {
            $this->attach(new Rook(PGN::COLOR_WHITE, 'a1'));
            $this->attach(new Knight(PGN::COLOR_WHITE, 'b1'));
            $this->attach(new Bishop(PGN::COLOR_WHITE, 'c1'));
            $this->attach(new Queen(PGN::COLOR_WHITE, 'd1'));
            $this->attach(new King(PGN::COLOR_WHITE, 'e1'));
            $this->attach(new Bishop(PGN::COLOR_WHITE, 'f1'));
            $this->attach(new Knight(PGN::COLOR_WHITE, 'g1'));
            $this->attach(new Rook(PGN::COLOR_WHITE, 'h1'));
            $this->attach(new Pawn(PGN::COLOR_WHITE, 'a2'));
            $this->attach(new Pawn(PGN::COLOR_WHITE, 'b2'));
            $this->attach(new Pawn(PGN::COLOR_WHITE, 'c2'));
            $this->attach(new Pawn(PGN::COLOR_WHITE, 'd2'));
            $this->attach(new Pawn(PGN::COLOR_WHITE, 'e2'));
            $this->attach(new Pawn(PGN::COLOR_WHITE, 'f2'));
            $this->attach(new Pawn(PGN::COLOR_WHITE, 'g2'));
            $this->attach(new Pawn(PGN::COLOR_WHITE, 'h2'));
            $this->attach(new Rook(PGN::COLOR_BLACK, 'a8'));
            $this->attach(new Knight(PGN::COLOR_BLACK, 'b8'));
            $this->attach(new Bishop(PGN::COLOR_BLACK, 'c8'));
            $this->attach(new Queen(PGN::COLOR_BLACK, 'd8'));
            $this->attach(new King(PGN::COLOR_BLACK, 'e8'));
            $this->attach(new Bishop(PGN::COLOR_BLACK, 'f8'));
            $this->attach(new Knight(PGN::COLOR_BLACK, 'g8'));
            $this->attach(new Rook(PGN::COLOR_BLACK, 'h8'));
            $this->attach(new Pawn(PGN::COLOR_BLACK, 'a7'));
            $this->attach(new Pawn(PGN::COLOR_BLACK, 'b7'));
            $this->attach(new Pawn(PGN::COLOR_BLACK, 'c7'));
            $this->attach(new Pawn(PGN::COLOR_BLACK, 'd7'));
            $this->attach(new Pawn(PGN::COLOR_BLACK, 'e7'));
            $this->attach(new Pawn(PGN::COLOR_BLACK, 'f7'));
            $this->attach(new Pawn(PGN::COLOR_BLACK, 'g7'));
            $this->attach(new Pawn(PGN::COLOR_BLACK, 'h7'));
        } else {
            foreach($pieces as $piece) {
                $this->attach($piece);
            }
        }

        $this->status = (object) [
            'turn' => null,
            'squares' => null,
            'control' => null,
            'previousMove' => (object) [
                PGN::COLOR_WHITE => (object) [
                    'identity' => null,
                    'position' => (object) [
                        'current' => null,
                        'next' => null
                ]],
                PGN::COLOR_BLACK => (object) [
                    'identity' => null,
                    'position' => (object) [
                        'current' => null,
                        'next' => null
                ]]
            ]
        ];

        $this->refresh();
    }

    /**
     * Gets the current board's status.
     *
     * @return stdClass
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Refreshes the board's status.
     *
     * @param PGNChess\Piece $piece
     * @return PGNChess\Board
     */
    private function refresh($piece=null)
    {
        // current player's turn
        $this->status->turn === PGN::COLOR_WHITE
            ? $this->status->turn = PGN::COLOR_BLACK
            : $this->status->turn = PGN::COLOR_WHITE;

        // compute square statistics and send them to all pieces
        $this->status->squares = Squares::stats(iterator_to_array($this, false));
        AbstractPiece::setSquares($this->status->squares);

        // compute control squares (space/attack squares)
        $this->status->control = $this->control();

        if (isset($piece)) {
            $this->trackCastling($piece);
            // compute previous moves and send them to all pieces
            $this->status->previousMove->{$piece->getColor()}->identity = $piece->getIdentity();
            $this->status->previousMove->{$piece->getColor()}->position = $piece->getMove()->position;
            AbstractPiece::setPreviousMove($this->status->previousMove);
        }
    }

    /**
     * Picks a piece to be moved.
     *
     * @param stdClass $move
     * @return array The piece(s) matching the PGN move; otherwise null.
     * @throws \InvalidArgumentException
     */
    private function pickPiece(\stdClass $move)
    {
        $found = [];
        $pieces = $this->getPiecesByColor($move->color);

        foreach ($pieces as $piece) {
            if ($piece->getIdentity() === $move->identity) {
                switch($piece->getIdentity()) {
                    case PGN::PIECE_KING:
                        $piece->setMove($move);
                        return [$piece];
                        break;
                    default:
                        if (preg_match("/{$move->position->current}/", $piece->getPosition()->current)) {
                            $piece->setMove($move);
                            $found[] = $piece;
                        }
                        break;
                }
            }
        }

        if (empty($found)) {
            throw new \InvalidArgumentException(
                "This piece does not exist on the board: {$move->color} {$move->identity} on {$move->position->current}"
            );
        } else {
            return $found;
        }
    }

    /**
     * Runs a chess move on the board.
     *
     * @param stdClass $move
     * @return boolean true if the move is successfully run; otherwise false
     */
    public function play(\stdClass $move)
    {
        $pieces = $this->pickPiece($move);

        if (count($pieces) > 1) {
            foreach ($pieces as $piece) {
                if ($piece->isMovable() && !$this->isCheck($piece)) {
                    return $this->move($piece);
                }
            }
        } elseif (count($pieces) == 1 && current($pieces)->isMovable() && !$this->isCheck(current($pieces))) {
            $piece = current($pieces);
            switch($piece->getMove()->type) {

                case PGN::MOVE_TYPE_KING:
                    return $this->kingIsMoved($piece);
                    break;

                case PGN::MOVE_TYPE_KING_CASTLING_SHORT:
                    if (
                        $piece->getCastling()->{PGN::CASTLING_SHORT}->canCastle &&
                        !(in_array(
                            PGN::castling($piece->getColor())->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->freeSquares->f,
                            $this->status->control->space->{$piece->getOppositeColor()})
                        ) &&
                        !(in_array(
                            PGN::castling($piece->getColor())->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->freeSquares->g,
                            $this->status->control->space->{$piece->getOppositeColor()}))
                    ) {
                        return $this->castle($piece);
                    } else {
                        return false;
                    }
                    break;

                case PGN::MOVE_TYPE_KING_CASTLING_LONG:
                    if (
                        $piece->getCastling()->{PGN::CASTLING_LONG}->canCastle &&
                        !(in_array(
                            PGN::castling($piece->getColor())->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->b,
                            $this->status->control->space->{$piece->getOppositeColor()})
                        ) &&
                        !(in_array(
                            PGN::castling($piece->getColor())->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->c,
                            $this->status->control->space->{$piece->getOppositeColor()})
                        ) &&
                        !(in_array(
                            PGN::castling($piece->getColor())->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->d,
                            $this->status->control->space->{$piece->getOppositeColor()}))
                    ) {
                        return $this->castle($piece);
                    } else {
                        return false;
                    }
                    break;

                default:
                    return $this->move($piece);
                    break;
            }
        } else {
            return false;
        }
    }

    /**
     * Moves the king.
     *
     * @param King $king
     * @return boolean true if the king captured the piece; otherwise false
     */
    private function kingIsMoved(King $king)
    {
        switch ($king->getMove()->type) {
            case PGN::MOVE_TYPE_KING:
                if (!in_array($king->getMove()->position->next,
                    $this->status->control->space->{$king->getOppositeColor()})) {
                    return $this->move($king);
                } else {
                    return false;
                }
                break;
           /* the piece to be captured is removed from the cloned board, and then
            * the king goes to the captured piece's square. If it is on a square
            * controlled by the opponent, it can't capture the piece.
            */
            case PGN::MOVE_TYPE_KING_CAPTURES:
                $that = clone $this;
                $capturedPiece = $that->getPieceByPosition($king->getMove()->position->next);
                $that->detach($capturedPiece);
                return $that->kingIsMoved($king);
                break;
        }
    }

    /**
     * Updates the king's castling property.
     *
     * @param Piece $piece
     */
    private function trackCastling(Piece $piece)
    {
        if ($piece->getMove()->type === PGN::MOVE_TYPE_KING) {
            $piece->updateCastling();
        } elseif (
            $piece->getMove()->type === PGN::MOVE_TYPE_PIECE && $piece->getIdentity() === PGN::PIECE_ROOK) {
            $king = $this->getPiece($piece->getColor(), PGN::PIECE_KING);
            $piece->updateCastling($king); // king passed by reference
        }
    }

    /**
     * Castles the king.
     *
     * @param PGNChess\Piece\King $king
     * @return boolean true if the castling is successfully run; otherwise false.
     */
    private function castle(King $king)
    {
        try {
            $rook = $king->getCastlingRook(iterator_to_array($this, false));
            switch(empty($rook)) {
                case false:
                    // move the king
                    $kingsNewPosition = $king->getPosition();
                    $kingsNewPosition->current = PGN::castling($king->getColor())
                        ->{PGN::PIECE_KING}->{$king->getMove()->pgn}->position->next;
                    $king->setPosition($kingsNewPosition)->setIsCastled();
                    $this->move($king);
                    // move the castling rook
                    $rooksNewPosition = $rook->getPosition();
                    $rooksNewPosition->current = PGN::castling($king->getColor())
                        ->{PGN::PIECE_ROOK}->{$king->getMove()->pgn}->position->next;
                    $rook->setMove((object) [
                        'type' => $king->getMove()->type,
                        'isCapture' => $king->getMove()->isCapture,
                        'position' => (object) ['next' => $rooksNewPosition->current]
                    ]);
                    $this->move($rook);
                    return true;
                    break;

                case true:
                    return false;
                    break;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Promotes a pawn.
     *
     * @param Pawn $pawn
     */
    private function promote(Pawn $pawn)
    {
        switch($pawn->getMove()->newIdentity) {
            case PGN::PIECE_KNIGHT:
                $this->attach(new Knight($pawn->getColor(), $pawn->getMove()->position->next));
                break;
            case PGN::PIECE_BISHOP:
                $this->attach(new Bishop($pawn->getColor(), $pawn->getMove()->position->next));
                break;
            case PGN::PIECE_ROOK:
                $this->attach(new Rook($pawn->getColor(), $pawn->getMove()->position->next));
                break;
            default:
                $this->attach(new Queen($pawn->getColor(), $pawn->getMove()->position->next));
                break;
        }

        $this->detach($pawn);
    }

    /**
     * Moves a piece.
     *
     * @param PGNChess\Piece\Piece $piece
     * @return boolean true if the move is successfully performed; otherwise false
     */
    private function move(Piece $piece)
    {
        try {
            // move piece
            $pieceClass = new \ReflectionClass(get_class($piece));
            $this->detach($piece);
            $this->attach($pieceClass->newInstanceArgs([
                $piece->getColor(),
                $piece->getMove()->position->next]
            ));

            // remove the captured piece, if any, from the board
            if($piece->getMove()->isCapture) {
                $this->detach(
                    $this->getPieceByPosition($piece->getMove()->position->next)
                );
            }

            // promote if the piece is a pawn
            if ($piece->getIdentity() === PGN::PIECE_PAWN  && $piece->isPromoted()) {
                $this->promote($piece);
            }

            $this->refresh($piece);

        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Gets all pieces by color.
     *
     * @param string $color
     * @return array
     */
    private function getPiecesByColor($color)
    {
        $pieces = [];
        $this->rewind();

        while ($this->valid()) {
            $piece = $this->current();
            $piece->getColor() === $color ? $pieces[] = $piece : false;
            $this->next();
        }

        return $pieces;
    }

    /**
     * Gets the first piece on the board meeting the search criteria.
     *
     * @param string $color
     * @param string $identity
     * @return PGNChess\Piece
     */
    private function getPiece($color, $identity)
    {
        $this->rewind();

        while ($this->valid()) {
            $piece = $this->current();
            if ($piece->getColor() === $color && $piece->getIdentity() === $identity) {
                return $piece;
            }
            $this->next();
        }

        return null;
    }

    /**
     * Gets a piece by its position on the board.
     *
     * @param string $square
     * @return PGNChess\Piece
     */
    private function getPieceByPosition($square)
    {
        $this->rewind();

        while ($this->valid()) {
            $piece = $this->current();
            if ($piece->getPosition()->current === $square) {
                return $piece;
            }
            $this->next();
        }

        return null;
    }

   /**
    * Builds an object containing the squares being controlled by both players.
    *
    * @return stdClass
    */
    private function control()
    {
        $control = (object) [
            'space' => (object) [
                PGN::COLOR_WHITE => [],
                PGN::COLOR_BLACK => []
            ],
            'attack' => (object) [
                PGN::COLOR_WHITE => [],
                PGN::COLOR_BLACK => []
        ]];

        $this->rewind();

        while ($this->valid()) {
            $piece = $this->current();
            switch($piece->getIdentity()) {

                case PGN::PIECE_KING:
                    $control->space->{$piece->getColor()} = array_unique(
                        array_merge(
                            $control->space->{$piece->getColor()},
                            array_values(
                                array_intersect(
                                    array_values((array)$piece->getPosition()->scope),
                                    $this->status->squares->free
                    ))));
                    $control->attack->{$piece->getColor()} = array_unique(
                        array_merge(
                            $control->attack->{$piece->getColor()},
                            array_values(
                                array_intersect(
                                    array_values((array)$piece->getPosition()->scope),
                                    $this->status->squares->used->{$piece->getOppositeColor()}
                    ))));
                    break;

                case PGN::PIECE_PAWN:
                    $control->space->{$piece->getColor()} = array_unique(
                        array_merge(
                            $control->space->{$piece->getColor()},
                            array_intersect(
                                $piece->getPosition()->capture,
                                $this->status->squares->free
                    )));
                    $control->attack->{$piece->getColor()} = array_unique(
                        array_merge(
                            $control->attack->{$piece->getColor()},
                            array_intersect(
                                $piece->getPosition()->capture,
                                $this->status->squares->used->{$piece->getOppositeColor()}
                    )));
                    break;

                default:
                    $control->space->{$piece->getColor()} = array_unique(
                        array_merge(
                            $control->space->{$piece->getColor()},
                            array_diff(
                                $piece->getLegalMoves(),
                                $this->status->squares->used->{$piece->getOppositeColor()}
                    )));
                    $control->attack->{$piece->getColor()} = array_unique(
                        array_merge(
                            $control->attack->{$piece->getColor()},
                            array_intersect(
                                $piece->getLegalMoves(),
                                $this->status->squares->used->{$piece->getOppositeColor()}
                    )));
                    break;
            }
            $this->next();
        }

        sort($control->space->{PGN::COLOR_WHITE});
        sort($control->space->{PGN::COLOR_BLACK});
        sort($control->attack->{PGN::COLOR_WHITE});
        sort($control->attack->{PGN::COLOR_BLACK});

        return $control;
    }

    /**
     * Verifies whether or not a piece's move leaves the board in check.
     *
     * @param PGNChess\Piece $piece
     * @return boolean
     */
    private function isCheck($piece)
    {
        $that = clone $this;
        $that->move($piece);
        $king = $that->getPiece($piece->getColor(), PGN::PIECE_KING);

        if (in_array($king->getPosition()->current, $that->control()->attack->{$king->getOppositeColor()})) {
            return true;
        } else {
            return false;
        }
    }
}
