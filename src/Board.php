<?php
namespace PGNChess;

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
final class Board extends \SplObjectStorage
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
     * Captured pieces.
     * 
     * @var stdClass
     */
    private $capturedPieces;
    
    /**
     * History.
     * 
     * @var array 
     */
    private $history;

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
        
        $this->capturedPieces = (object) [
            Symbol::WHITE => [],
            Symbol::BLACK => []
        ];
        
        $this->history = [];

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
     * @return Board
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
     * @return Board
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
     * @return Board
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
     * Gets the captured pieces of both players.
     *
     * @return stdClass
     */
    public function getCapturedPieces()
    {
        return $this->capturedPieces;
    }

    /**
     * Adds a piece to the captured pieces object.
     * 
     * @param string $color
     * @param array $piece
     * @return Board
     */
    private function addCapturedPiece($color, $piece)
    {
        $this->capturedPieces->{$color}[] = $piece;

        return $this;
    }
    
    /**
     * Gets the history.
     * 
     * @return array
     */
    public function getHistory()
    {
        return $this->history;
    }
    
    /**
     * Adds a new entry to the history.
     * 
     * @param type $move
     * @return Board
     */
    private function addHistoryEntry($move)
    {
        $this->history[] = $move;
        
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
            $this->addHistoryEntry($piece->getMove());
            $previousMove = end($this->history);
        }

        $this->turn === Symbol::WHITE ? $this->turn = Symbol::BLACK : $this->turn = Symbol::WHITE;

        $this->squares = Stats::calc(iterator_to_array($this, false));
        
        AbstractPiece::setBoardStatus((object)[
            'squares' => $this->squares,
            'castling' => $this->castling,
            'previousMove' => isset($previousMove) ? $previousMove : null 
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
                        return [$piece->setMove($move)];
                        break;
                    
                    default:
                        if (preg_match("/{$move->position->current}/", $piece->getPosition()->current)) {
                            $found[] = $piece->setMove($move);
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
     * Captures a piece.
     * 
     * @param Piece $piece
     */
    private function capture(Piece $piece) 
    {
        $capturedPiece = $this->getPieceByPosition($piece->getMove()->position->next);
        
        switch ($piece->getIdentity()) {
            
            case Symbol::PAWN:
                $piece->getLegalMoves(); // this creates the enPassantSquare property in the pawn's position object
                if (!empty($piece->getPosition()->enPassantSquare)) {
                    $capturedPiece = $this->getPieceByPosition($piece->getPosition()->enPassantSquare);
                    $this->detach($capturedPiece);
                } else {
                    $capturedPiece = $this->getPieceByPosition($piece->getMove()->position->next);
                    $this->detach($capturedPiece);
                }
                break;
            
            default:
                $capturedPiece = $this->getPieceByPosition($piece->getMove()->position->next);
                $this->detach($capturedPiece);
                break;            
        }
        
        $capturedPieceData = (object) [
            'identity' => $capturedPiece->getIdentity(),
            'position' => $capturedPiece->getPosition()->current
        ];
        
        $capturedPiece->getIdentity() === Symbol::ROOK 
            ? $capturedPieceData->type = $capturedPiece->getType()
            : null;
        
        $this->addCapturedPiece($piece->getColor(), $capturedPieceData);
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
                if ($piece->isMovable() && !$this->leavesInCheck($piece)) {
                    return $this->move($piece);
                }
            }
        } elseif (count($pieces) == 1 && current($pieces)->isMovable() && !$this->leavesInCheck(current($pieces))) {
            
            $piece = current($pieces);
            
            switch($piece->getMove()->type) {

                case Move::KING_CASTLING_SHORT:
                    if (
                        $this->castling->{$this->turn}->{Symbol::CASTLING_SHORT} &&
                        !(in_array(
                            Castling::info($this->turn)->{Symbol::KING}->{Symbol::CASTLING_SHORT}->squares->f,
                            $this->control->space->{$piece->getOppositeColor()})
                        ) &&
                        !(in_array(
                            Castling::info($this->turn)->{Symbol::KING}->{Symbol::CASTLING_SHORT}->squares->g,
                            $this->control->space->{$piece->getOppositeColor()}))
                    ) {
                        return $this->castle($piece);
                    } else {
                        return false;
                    }
                    break;

                case Move::KING_CASTLING_LONG:
                    if (
                        $this->castling->{$this->turn}->{Symbol::CASTLING_LONG} &&
                        !(in_array(
                            Castling::info($this->turn)->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->b,
                            $this->control->space->{$piece->getOppositeColor()})
                        ) &&
                        !(in_array(
                            Castling::info($this->turn)->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->c,
                            $this->control->space->{$piece->getOppositeColor()})
                        ) &&
                        !(in_array(
                            Castling::info($this->turn)->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->d,
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
     * Updates the king's ability to castle.
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
                    $this->attach(new King(
                        $king->getColor(), 
                        Castling::info($king->getColor())->{Symbol::KING}->{$king->getMove()->pgn}->position->next)
                    );
                    $this->detach($king);
                    
                    // move the castling rook
                    $this->attach(new Rook(
                        $rook->getColor(), 
                        Castling::info($king->getColor())->{Symbol::ROOK}->{$king->getMove()->pgn}->position->next,
                        $rook->getIdentity() === Symbol::ROOK
                    ));
                    $this->detach($rook);
                    
                    // update the king's castling status
                    $this->castling->{$king->getColor()}->castled = true;
                    $this->trackCastling($king);
                    
                    // refresh the board's status
                    $this->refresh($king);
                    
                    return true;
                    break;

                case true:
                    return false;
                    break;
            }
            
        } catch (\Exception $e) {
            throw new BoardException(
                "Error castling: {$piece->getColor()} {$piece->getIdentity()} on {$piece->getMove()->position->next}."
            );
        }
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

            // remove the captured piece from the board -- if any
            if ($piece->getMove()->isCapture) {
                $this->capture($piece);
            }

            // try to promote if the piece is a pawn
            if ($piece->getIdentity() === Symbol::PAWN  && $piece->isPromoted()) {
                $this->promote($piece);
            }
            
            $this->detach($piece);
            $this->refresh($piece);

        } catch (\Exception $e) {
            throw new BoardException(
                "Error moving: {$piece->getColor()} {$piece->getIdentity()} on {$piece->getMove()->position->next}."
            );
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
     * Calculates whether or not a piece's move leaves the board in check.
     *
     * @param Piece $piece
     * @return boolean
     */
    private function leavesInCheck($piece)
    {
        $that = new \stdClass;
        
        $that->board =  unserialize(serialize($this));
        $that->piece = $that->board->getPieceByPosition($piece->getPosition()->current);
        $that->board->move($that->piece->setMove($piece->getMove()));
        $that->king = $that->board->getPiece($that->piece->getColor(), Symbol::KING);

        return in_array(
            $that->king->getPosition()->current, 
            $that->board->getControl()->attack->{$that->king->getOppositeColor()}
        );
    }
    
    /**
     * Calculates whether the current player is in check.
     * 
     * @return boolean
     */
    public function isCheck()
    {
        $king = $this->getPiece($this->turn, Symbol::KING);

        return in_array(
            $king->getPosition()->current, 
            $this->control->attack->{$king->getOppositeColor()}
        );
    }
    
    /**
     * Calculates whether the current player is in mate.
     * 
     * @return boolean
     */
    public function isMate()
    {
        $escape = 0;
        
        $that = new \stdClass;
        $that->board = unserialize(serialize($this));
        $that->pieces = [];
        
        $currentTurnPieces = $that->board->getPiecesByColor($that->board->turn);
        
        foreach ($currentTurnPieces as $piece) {
            $that->pieces[] = (object) [
               'o' => $piece,
               'legalMoves' => $piece->getLegalMoves()
           ];
        }
        
        foreach ($that->pieces as $piece) {
            
            foreach($piece->legalMoves as $square) {
                
                switch($piece->o->getIdentity()) {
                        
                    case Symbol::KING:
                        if (in_array($square, $that->board->getSquares()->used->{$piece->o->getOppositeColor()})) {
                            $escape += (int)!$that->board->leavesInCheck(
                                $piece->o->setMove(
                                    Convert::toObject($that->board->getTurn(), Symbol::KING . "x$square")
                            ));
                        }
                        elseif (!in_array($square, $that->board->getControl()->space->{$piece->o->getOppositeColor()})) {
                            $escape += (int) !$that->board->leavesInCheck(
                                $piece->o->setMove(
                                    Convert::toObject($that->board->getTurn(), Symbol::KING . $square)
                            ));                            
                        }
                        break;

                    case Symbol::PAWN:
                        if (in_array($square, $that->board->getSquares()->used->{$piece->o->getOppositeColor()})) {
                            $escape += (int) !$that->board->leavesInCheck(
                                $piece->o->setMove(
                                    Convert::toObject($that->board->getTurn(), $piece->o->getFile() . "x$square")
                            ));
                        } else {
                            $escape += (int) !$that->board->leavesInCheck(
                                $piece->o->setMove(Convert::toObject($that->board->getTurn(), $square)
                            ));
                        }
                        break;

                    default:
                        if (in_array($square, $that->board->getSquares()->used->{$piece->o->getOppositeColor()})) {
                            $escape += (int) !$that->board->leavesInCheck(
                                $piece->o->setMove(
                                    Convert::toObject($that->board->getTurn(), $piece->o->getIdentity() . "x$square")
                            ));
                        } else {
                            $escape += (int) !$that->board->leavesInCheck(
                                $piece->o->setMove(
                                    Convert::toObject($that->board->getTurn(), $piece->o->getIdentity() . $square)
                            ));                         
                        }
                        break;
                    }
                
                }         
        }
        
        return $escape === 0;        
    }
}
