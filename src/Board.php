<?php
namespace PGNChess;

use DeepCopy\DeepCopy;
use PGNChess\Exception\BoardException;
use PGNChess\Square\Castling;
use PGNChess\Square\Stats;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Move;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validate;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Piece;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Type\RookType;

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
     * Current player's turn.
     *
     * @var string
     */
    private $turn;

    /**
     * Free/used squares.
     *
     * @var stdClass
     */
    private $squares;

    /**
     * Squares controlled by both players.
     *
     * @var stdClass
     */
    private $control;

    /**
     * Castling status.
     *
     * @var stdClass
     */
    private $castling;

    /**
     * Previous move.
     *
     * @var stdClass
     */
    private $previousMove;

    /**
     * Constructor.
     *
     * @param array $pieces
     * @param stdClass $castling
     */
    public function __construct(array $pieces=null, $castling=null)
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

            $this->castling = (object) [
                Symbol::WHITE => (object) [
                    'castled' => false,
                    Symbol::CASTLING_SHORT => true,
                    Symbol::CASTLING_LONG => true
                ],
                Symbol::BLACK => (object) [
                    'castled' => false,
                    Symbol::CASTLING_SHORT => true,
                    Symbol::CASTLING_LONG => true
            ]];

        } else {

            foreach($pieces as $piece) {
                $this->attach($piece);
            }

            $this->castling = $castling;

            Analyze::castling($this);
        }

        $this->previousMove = (object) [
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
        ];

        $this->refresh();
    }

    /**
     * Gets the current turn.
     *
     * @return string
     */
    public function getTurn()
    {
        return $this->turn;
    }

    /**
     * Sets the current turn.
     *
     * @param string $turn
     */
    public function setTurn($turn)
    {
        $this->turn = Validate::color($turn);

        return $this;
    }

    /**
     * Gets the free/used squares.
     *
     * @return stdClass
     */
    public function getSquares()
    {
        return $this->squares;
    }

    /**
     * Sets the free/used squares.
     *
     * @param stdClass $squares
     */
    private function setSquares($squares)
    {
        $this->squares = $squares;

        return $this;
    }

    /**
     * Gets the squares controlled by both players.
     *
     * @return stdClass
     */
    public function getControl()
    {
        return $this->control;
    }

    /**
     * Sets the squares controlled by both players.
     *
     * @param stdClass $control
     */
    private function setControl($control)
    {
        $this->control = $control;

        return $this;
    }

    /**
     * Gets the castling status.
     *
     * @return stdClass
     */
    public function getCastling()
    {
        return $this->castling;
    }

    /**
     * Sets the castling status.
     *
     * @param stdClass $castling
     */
    private function setCastling($castling)
    {
        $this->castling = $castling;

        return $this;
    }

    /**
     * Gets the previous move.
     *
     * @return stdClass
     */
    public function getPreviousMove()
    {
        return $this->previousMove;
    }

    /**
     * Sets the previous move.
     *
     * @param stdClass $previousMove
     */
    private function setPreviousMove($previousMove)
    {
        $this->previousMove = $previousMove;

        return $this;
    }

    /**
     * Refreshes the board's status.
     *
     * This method is run just after a piece is moved successfully.
     *
     * @param Piece $piece
     * @return Board
     */
    private function refresh($piece=null)
    {
        if (isset($piece)) {
            if (!$this->castling->{$piece->getColor()}->castled) {
                $this->trackCastling($piece);
            }
            $this->previousMove->{$piece->getColor()} = (object) [
                'identity' => $piece->getIdentity(),
                'position' => $piece->getMove()->position
            ];
        }

        $this->turn === Symbol::WHITE ? $this->turn = Symbol::BLACK : $this->turn = Symbol::WHITE;

        $this->squares = Stats::calc(iterator_to_array($this, false));

        AbstractPiece::setBoardStatus((object)[
            'squares' => $this->squares,
            'castling' => $this->castling,
            'previousMove' => $this->previousMove
        ]);

        $this->control = $this->control();
    }

    /**
     * Picks a piece to be moved.
     *
     * @param stdClass $move
     * @return array The piece(s) matching the PGN move; otherwise null.
     * @throws BoardException
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
            throw new BoardException(
                "This piece does not exist: {$move->color} {$move->identity} on {$move->position->current}."
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
        if ($move->color !== $this->turn) {
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
                        $this->castling->{$piece->getColor()}->{Symbol::CASTLING_SHORT} &&
                        !(in_array(
                            Castling::info($piece->getColor())->{Symbol::KING}->{Symbol::CASTLING_SHORT}->freeSquares->f,
                            $this->control->space->{$piece->getOppositeColor()})
                        ) &&
                        !(in_array(
                            Castling::info($piece->getColor())->{Symbol::KING}->{Symbol::CASTLING_SHORT}->freeSquares->g,
                            $this->control->space->{$piece->getOppositeColor()}))
                    ) {
                        return $this->castle($piece);
                    } else {
                        return false;
                    }
                    break;

                case Move::KING_CASTLING_LONG:
                    if (
                        $this->castling->{$piece->getColor()}->{Symbol::CASTLING_LONG} &&
                        !(in_array(
                            Castling::info($piece->getColor())->{Symbol::KING}->{Symbol::CASTLING_LONG}->freeSquares->b,
                            $this->control->space->{$piece->getOppositeColor()})
                        ) &&
                        !(in_array(
                            Castling::info($piece->getColor())->{Symbol::KING}->{Symbol::CASTLING_LONG}->freeSquares->c,
                            $this->control->space->{$piece->getOppositeColor()})
                        ) &&
                        !(in_array(
                            Castling::info($piece->getColor())->{Symbol::KING}->{Symbol::CASTLING_LONG}->freeSquares->d,
                            $this->control->space->{$piece->getOppositeColor()}))
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
                    $this->control->space->{$king->getOppositeColor()})) {
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
        if ($piece->getIdentity() === Symbol::KING) {

            $this->castling->{$piece->getColor()}->{Symbol::CASTLING_SHORT} = false;
            $this->castling->{$piece->getColor()}->{Symbol::CASTLING_LONG} = false;

        } elseif ($piece->getIdentity() === Symbol::ROOK) {

            switch($piece->getType()) {

                case RookType::CASTLING_SHORT:
                    $this->castling->{$piece->getColor()}->{Symbol::CASTLING_SHORT} = false;
                    break;

                case RookType::CASTLING_LONG:
                    $this->castling->{$piece->getColor()}->{Symbol::CASTLING_LONG} = false;
                    break;

            }
        }
    }

    /**
     * Castles the king.
     *
     * @param King $king
     * @return boolean true if the castling is successfully run; otherwise false.
     */
    private function castle(King $king)
    {
        try {
            $rook = $king->getCastlingRook(iterator_to_array($this, false));

            switch(empty($rook)) {

                case false:
                    // move the king
                    $kingsMove = $king->getMove();
                    $kingsMove->position = (object) [
                        'next' => Castling::info($king->getColor())
                                    ->{Symbol::KING}->{$king->getMove()->pgn}->position->next
                    ];
                    $king->setMove($kingsMove);
                    $this->move($king);
                    // move the castling rook
                    $rook->setMove((object) [
                        'isCapture' => false, // capture is always false when castling
                        'isCheck' => $king->getMove()->isCheck,
                        'type' => $king->getMove()->type,
                        'color' => $king->getColor(),
                        'identity' => Symbol::ROOK,
                        'position' => (object) [
                            'next' => Castling::info($king->getColor())
                                        ->{Symbol::ROOK}->{$king->getMove()->pgn}->position->next
                        ]
                    ]);
                    $this->move($rook);
                    // update the king's castling status
                    $this->castling->{$king->getColor()}->castled = true;
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
     * @param Piece $piece
     * @return boolean true if the move is successfully performed; otherwise false
     */
    private function move(Piece $piece)
    {
        try {
            // move the piece
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
    public function getPiecesByColor($color)
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
     * @return Piece
     */
    public function getPiece($color, $identity)
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
     * @return Piece
     */
    public function getPieceByPosition($square)
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
                                    $this->squares->free
                    ))));
                    $control->attack->{$piece->getColor()} = array_unique(
                        array_merge(
                            $control->attack->{$piece->getColor()},
                            array_values(
                                array_intersect(
                                    array_values((array)$piece->getPosition()->scope),
                                    $this->squares->used->{$piece->getOppositeColor()}
                    ))));
                    break;

                case Symbol::PAWN:
                    $control->space->{$piece->getColor()} = array_unique(
                        array_merge(
                            $control->space->{$piece->getColor()},
                            array_intersect(
                                $piece->getPosition()->capture,
                                $this->squares->free
                    )));
                    $control->attack->{$piece->getColor()} = array_unique(
                        array_merge(
                            $control->attack->{$piece->getColor()},
                            array_intersect(
                                $piece->getPosition()->capture,
                                $this->squares->used->{$piece->getOppositeColor()}
                    )));
                    break;

                default:
                    $control->space->{$piece->getColor()} = array_unique(
                        array_merge(
                            $control->space->{$piece->getColor()},
                            array_diff(
                                $piece->getLegalMoves(),
                                $this->squares->used->{$piece->getOppositeColor()}
                    )));
                    $control->attack->{$piece->getColor()} = array_unique(
                        array_merge(
                            $control->attack->{$piece->getColor()},
                            array_intersect(
                                $piece->getLegalMoves(),
                                $this->squares->used->{$piece->getOppositeColor()}
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
     * @param Piece $piece
     * @return boolean
     */
    private function isCheck($piece)
    {
        $deepCopy = new DeepCopy();
        $that = $deepCopy->copy($this);
        $that->move($piece);
        $king = $that->getPiece($piece->getColor(), Symbol::KING);

        if (in_array($king->getPosition()->current, $that->getControl()->attack->{$king->getOppositeColor()})) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Replicates the board for cloning purposes.
    *
    * @return Board
    */
    public function replicate()
    {
        $boardClass = new \ReflectionClass(get_class());
        $boardReplicated = $boardClass->newInstanceArgs([iterator_to_array($this, false), $this->getCastling()]);

        $boardReplicated
            ->setTurn($this->getTurn())
            ->setSquares($this->getSquares())
            ->setControl($this->getControl())
            ->setPreviousMove($this->getPreviousMove());

        return $boardReplicated;
    }
}
