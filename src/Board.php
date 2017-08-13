<?php
namespace PGNChess;

use DeepCopy\DeepCopy;
use PGNChess\Castling;
use PGNChess\SquareStats;
use PGNChess\PGN\Validator;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Piece;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;
use PGNChess\PGN\Move;
use PGNChess\PGN\Symbol;
use PGNChess\Type\RookType;

/**
 * Chess board.
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
            $this->attach(new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG));
            $this->attach(new Knight(Symbol::WHITE, 'b1'));
            $this->attach(new Bishop(Symbol::WHITE, 'c1'));
            $this->attach(new Queen(Symbol::WHITE, 'd1'));
            $this->attach(new King(Symbol::WHITE, 'e1'));
            $this->attach(new Bishop(Symbol::WHITE, 'f1'));
            $this->attach(new Knight(Symbol::WHITE, 'g1'));
            $this->attach(new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT));
            $this->attach(new Pawn(Symbol::WHITE, 'a2'));
            $this->attach(new Pawn(Symbol::WHITE, 'b2'));
            $this->attach(new Pawn(Symbol::WHITE, 'c2'));
            $this->attach(new Pawn(Symbol::WHITE, 'd2'));
            $this->attach(new Pawn(Symbol::WHITE, 'e2'));
            $this->attach(new Pawn(Symbol::WHITE, 'f2'));
            $this->attach(new Pawn(Symbol::WHITE, 'g2'));
            $this->attach(new Pawn(Symbol::WHITE, 'h2'));
            $this->attach(new Rook(Symbol::BLACK, 'a8', RookType::CASTLING_LONG));
            $this->attach(new Knight(Symbol::BLACK, 'b8'));
            $this->attach(new Bishop(Symbol::BLACK, 'c8'));
            $this->attach(new Queen(Symbol::BLACK, 'd8'));
            $this->attach(new King(Symbol::BLACK, 'e8'));
            $this->attach(new Bishop(Symbol::BLACK, 'f8'));
            $this->attach(new Knight(Symbol::BLACK, 'g8'));
            $this->attach(new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT));
            $this->attach(new Pawn(Symbol::BLACK, 'a7'));
            $this->attach(new Pawn(Symbol::BLACK, 'b7'));
            $this->attach(new Pawn(Symbol::BLACK, 'c7'));
            $this->attach(new Pawn(Symbol::BLACK, 'd7'));
            $this->attach(new Pawn(Symbol::BLACK, 'e7'));
            $this->attach(new Pawn(Symbol::BLACK, 'f7'));
            $this->attach(new Pawn(Symbol::BLACK, 'g7'));
            $this->attach(new Pawn(Symbol::BLACK, 'h7'));
        } else {
            foreach($pieces as $piece) {
                $this->attach($piece);
            }
        }

        $this->status = (object) [
            'turn' => null,
            'squares' => null,
            'control' => null,
            'isChecked' => (object) [
                Symbol::WHITE => false,
                Symbol::BLACK => false
            ],
            'isCheckemated' => (object) [
                Symbol::WHITE => false,
                Symbol::BLACK => false
            ],
            'castling' => (object) [
                Symbol::WHITE => (object) [
                    'isCastled' => false,
                    Symbol::CASTLING_SHORT => true,
                    Symbol::CASTLING_LONG => true
                ],
                Symbol::BLACK => (object) [
                    'isCastled' => false,
                    Symbol::CASTLING_SHORT => true,
                    Symbol::CASTLING_LONG => true
            ]],
            'previousMove' => (object) [
                Symbol::WHITE => (object) [
                    'identity' => null,
                    'position' => (object) [
                        'current' => null,
                        'next' => null
                ]],
                Symbol::BLACK => (object) [
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
     * Sets the turn.
     *
     * @param string $color
     */
    public function setTurn($color)
    {
        !Validator::color($color) ?: $this->status->turn = $color;
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
        $this->status->turn === Symbol::WHITE
            ? $this->status->turn = Symbol::BLACK
            : $this->status->turn = Symbol::WHITE;

        // compute square statistics and send them to all pieces
        $this->status->squares = SquareStats::calc(iterator_to_array($this, false));
        AbstractPiece::setSquares($this->status->squares);

        // compute control squares (space/attack squares)
        $this->status->control = $this->control();

        if (isset($piece)) {
            // compute previous moves and send them to all pieces
            $this->status->previousMove->{$piece->getColor()}->identity = $piece->getIdentity();
            $this->status->previousMove->{$piece->getColor()}->position = $piece->getMove()->position;
            AbstractPiece::setPreviousMove($this->status->previousMove);
            // update checked property
            $king = $this->getPiece($piece->getOppositeColor(), Symbol::KING);
            if (in_array($king->getPosition()->current, $this->status->control->attack->{$king->getOppositeColor()})) {
                $this->status->isChecked->{$piece->getOppositeColor()} = true;
            } else {
                $this->status->isChecked->{$piece->getOppositeColor()} = false;
            }
            // check if the game is over
            $this->isGameOver($piece);
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
                    case Symbol::KING:
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
        if ($move->color !== $this->status->turn) {
            return false;
        }

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

                case Move::KING:
                    return $this->kingIsMoved($piece);
                    break;

                case Move::KING_CASTLING_SHORT:
                    if (
                        $this->status->castling->{$piece->getColor()}->{Symbol::CASTLING_SHORT} &&
                        !(in_array(
                            Castling::info($piece->getColor())->{Symbol::KING}->{Symbol::CASTLING_SHORT}->freeSquares->f,
                            $this->status->control->space->{$piece->getOppositeColor()})
                        ) &&
                        !(in_array(
                            Castling::info($piece->getColor())->{Symbol::KING}->{Symbol::CASTLING_SHORT}->freeSquares->g,
                            $this->status->control->space->{$piece->getOppositeColor()}))
                    ) {
                        return $this->castle($piece);
                    } else {
                        return false;
                    }
                    break;

                case Move::KING_CASTLING_LONG:
                    if (
                        $this->status->castling->{$piece->getColor()}->{Symbol::CASTLING_LONG} &&
                        !(in_array(
                            Castling::info($piece->getColor())->{Symbol::KING}->{Symbol::CASTLING_LONG}->freeSquares->b,
                            $this->status->control->space->{$piece->getOppositeColor()})
                        ) &&
                        !(in_array(
                            Castling::info($piece->getColor())->{Symbol::KING}->{Symbol::CASTLING_LONG}->freeSquares->c,
                            $this->status->control->space->{$piece->getOppositeColor()})
                        ) &&
                        !(in_array(
                            Castling::info($piece->getColor())->{Symbol::KING}->{Symbol::CASTLING_LONG}->freeSquares->d,
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
            case Move::KING:
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
            case Move::KING_CAPTURES:
                $deepCopy = new DeepCopy();
                $that = $deepCopy->copy($this);
                $that->detach($that->getPieceByPosition($king->getMove()->position->next));
                return $that->kingIsMoved($king);
                break;
        }
    }

    /**
     * Updates the kings' ability to castle.
     *
     * @param Piece $piece
     */
    private function trackCastling(Piece $piece)
    {
        if ($piece->getMove()->type === Move::KING) {

            $this->status->castling->{$piece->getColor()}->{Symbol::CASTLING_SHORT} = false;
            $this->status->castling->{$piece->getColor()}->{Symbol::CASTLING_LONG} = false;

        } elseif ($piece->getMove()->type === Move::PIECE && $piece->getIdentity() === Symbol::ROOK) {

            switch($piece->getType()) {
                case RookType::CASTLING_SHORT:
                    $this->status->castling->{$piece->getColor()}->{Symbol::CASTLING_SHORT} = false;
                    break;
                case RookType::CASTLING_LONG:
                    $this->status->castling->{$piece->getColor()}->{Symbol::CASTLING_LONG} = false;
                    break;
                default:
                    // ...
                    break;
            }
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
                    $kingsNewPosition->current = Castling::info($king->getColor())
                        ->{Symbol::KING}->{$king->getMove()->pgn}->position->next;
                    $king->setPosition($kingsNewPosition);
                    $this->move($king);
                    // move the castling rook
                    $rooksNewPosition = $rook->getPosition();
                    $rooksNewPosition->current = Castling::info($king->getColor())
                        ->{Symbol::ROOK}->{$king->getMove()->pgn}->position->next;
                    $rook->setMove((object) [
                        'type' => $king->getMove()->type,
                        'isCapture' => $king->getMove()->isCapture,
                        'position' => (object) ['next' => $rooksNewPosition->current]
                    ]);
                    $this->move($rook);
                    // update the king's castling status
                    $this->status->castling->{$king->getColor()}->isCastled = true;
                    // refresh board's status
                    $this->refresh($king);
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
            case Symbol::KNIGHT:
                $this->attach(new Knight($pawn->getColor(), $pawn->getMove()->position->next));
                break;
            case Symbol::BISHOP:
                $this->attach(new Bishop($pawn->getColor(), $pawn->getMove()->position->next));
                break;
            case Symbol::ROOK:
                $this->attach(new Rook($pawn->getColor(), $pawn->getMove()->position->next, RookType::PROMOTED));
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
            $this->attach($pieceClass->newInstanceArgs([
                $piece->getColor(),
                $piece->getMove()->position->next,
                $piece->getIdentity() === Symbol::ROOK ? $piece->getType(): null]
            ));
            $this->detach($piece);

            // remove from the board the captured piece, if any
            if($piece->getMove()->isCapture) {
                $this->detach(
                    $this->getPieceByPosition($piece->getMove()->position->next)
                );
            }

            // if the piece is a pawn, promote
            if ($piece->getIdentity() === Symbol::PAWN  && $piece->isPromoted()) {
                $this->promote($piece);
            }

            // track ability to castle
            if (!$this->status->castling->{$piece->getColor()}->isCastled) {
                $this->trackCastling($piece);
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
    public function control()
    {
        $control = (object) [
            'space' => (object) [
                Symbol::WHITE => [],
                Symbol::BLACK => []
            ],
            'attack' => (object) [
                Symbol::WHITE => [],
                Symbol::BLACK => []
        ]];

        $this->rewind();

        while ($this->valid()) {
            $piece = $this->current();
            switch($piece->getIdentity()) {

                case Symbol::KING:
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

                case Symbol::PAWN:
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

        sort($control->space->{Symbol::WHITE});
        sort($control->space->{Symbol::BLACK});
        sort($control->attack->{Symbol::WHITE});
        sort($control->attack->{Symbol::BLACK});

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
        $deepCopy = new DeepCopy();
        $that = $deepCopy->copy($this);
        $that->move($piece);
        $king = $that->getPiece($piece->getColor(), Symbol::KING);

        if (in_array($king->getPosition()->current, $that->getStatus()->control->attack->{$king->getOppositeColor()})) {
            return true;
        } else {
            return false;
        }
    }

    private function isGameOver($piece)
    {

    }
}
