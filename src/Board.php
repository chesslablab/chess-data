<?php
namespace PGNChess;

use PGNChess\PGN;
use PGNChess\Squares;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Piece;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;

/**
 * Class that represents a chess board. This is basically a container of chess
 * pieces that are constantly being updated/removed as players run their moves
 * on the board.
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
        if (empty($pieces))
        {
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
        }
        else
        {
            foreach($pieces as $piece)
            {
                $this->attach($piece);
            }
        }

        $this->status = (object) [
            'turn' => null,
            'castled' => (object) [
                PGN::COLOR_WHITE => false,
                PGN::COLOR_BLACK => false
            ]
        ];

        $this->updateStatus();
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
     * Updates the board's status.
     *
     * @return PGNChess\Board
     */
    private function updateStatus()
    {
        // update turn
        $this->status->turn === PGN::COLOR_WHITE
            ? $this->status->turn = PGN::COLOR_BLACK
            : $this->status->turn = PGN::COLOR_WHITE;

        // compute square statistics
        $this->status->squares = Squares::stats(iterator_to_array($this, false));

        // set square statistics (flat data) for all pieces
        AbstractPiece::setSquares($this->status->squares);
    }

    /**
     * Picks a piece to be moved, prioritizing the matching of the less ambiguous
     * one according to the PGN format. It returns the first available piece
     * that matches the criteria.
     *
     * @param stdClass $move
     *
     * @return array The piece(s) matching the PGN move; otherwise null.
     *
     * @throws \InvalidArgumentException
     */
    private function pickPieceToMove(\stdClass $move)
    {
        $found = [];
        $pieces = $this->getPiecesByColor($move->color);
        foreach ($pieces as $piece)
        {
            if ($piece->getIdentity() === $move->identity)
            {
                switch($piece->getIdentity())
                {
                    // the king is a non-ambiguous piece (there's only one)
                    case PGN::PIECE_KING:
                        $piece->setMove($move);
                        return [$piece];
                        break;

                    // pawns are non-ambiguous pieces
                    case PGN::PIECE_PAWN:
                        if (preg_match("/{$move->position->current}/", $piece->getPosition()->current))
                        {
                            $piece->setMove($move);
                            return [$piece];
                        }
                        break;

                    // the rest of pieces are potentially ambiguous and need
                    // to be disambiguated; for example, Rbe8, Q7g7. That is why
                    // they are stored in the $found array.
                    default:
                        if (preg_match("/{$move->position->current}/", $piece->getPosition()->current))
                        {
                            $piece->setMove($move);
                            $found[] = $piece;
                        }
                        break;
                }
            }
        }
        if (empty($found))
        {
            throw new \InvalidArgumentException("This piece does not exist on the board: {$move->color} {$move->identity} on {$move->position->current}");
        }
        else
        {
            return $found;
        }
    }

    /**
     * Runs a chess move on the board.
     *
     * Note that there are 3 different types of moves:
     *
     *      (1) kingIsMoved
     *      (2) castle
     *      (3) pieceIsMoved
     *
     * In all cases, you have to first pick the piece you want to move by calling
     * the pickPieceToMove(\stdClass $move) method -- which expects as an input
     * the objectized counterpart of a valid move in PGN notation. If it is the case
     * that the piece can be moved according to chess rules, the move will be run
     * and the chess board will be updated accordingly.
     *
     * @see PGN::objectizeMove($color, $pgn)
     *
     * @param stdClass $move
     *
     * @return boolean true if the move is successfully run; otherwise false
     */
    public function play(\stdClass $move)
    {
        $pieces = $this->pickPieceToMove($move);
        // the piece is disambiguated -- for example, Rbe8, Q7g7 -- by picking
        // the movable one from the array of potential, ambiguous pieces.
        if (count($pieces) > 1)
        {
            foreach ($pieces as $piece)
            {
                if ($piece->isMovable() && !$this->isCheck($piece))
                {
                    return $this->pieceIsMoved($piece);
                }
            }
        }
        // the current piece is not ambiguous (there's only one in the $pieces array)
        elseif (count($pieces) == 1 && current($pieces)->isMovable() && !$this->isCheck(current($pieces)))
        {
            $piece = current($pieces);
            switch($piece->getMove()->type)
            {
                case PGN::MOVE_TYPE_KING:
                    return $this->kingIsMoved($piece);
                    break;

                case PGN::CASTLING_SHORT:
                    return $this->castle($piece);
                    break;

                case PGN::CASTLING_LONG:
                    return $this->castle($piece);
                    break;

                default:
                    return $this->pieceIsMoved($piece);
                    break;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Moves the king.
     *
     * @see Board::space()
     *
     * @param King $king
     *
     * @return boolean true if the king captured the piece; otherwise false
     */
    private function kingIsMoved(King $king)
    {
        switch ($king->getMove()->type)
        {
            /*
            * This decision can be made thanks to the implementation of
            * the concept of space -- the squares controlled by both players.
            */
            case PGN::MOVE_TYPE_KING:
                if (!in_array($king->getMove()->position->next, $this->space()->{$king->getOppositeColor()}))
                {
                    return $this->pieceIsMoved($king);
                }
                else
                {
                    return false;
                }
                break;

            /*
            * This is like going to the future to see what will happen in the next
            * move in order to take a decision accordingly. It forks the current board
            * and simulates the king's capture move on it. Here is the idea actually being
            * implemented: (1) the piece to be captured is removed from the forked board,
            * and (2) then the king moves to the square where the captured piece should be
            * standing. Following this logical sequence, if it turns out that the king is
            * on a square controlled by the opponent, the king can't capture the piece.
            * This way we can reuse the method implementing a normal king's move. Alternatively,
            * you could build an array/object containing the pieces defended among themselves
            * according to chess rules. However, in this particular case the strategy of going
            * to the future is easier to carry out.
            */
            case PGN::MOVE_TYPE_KING_CAPTURES:
                $that = $this;
                $capturedPiece = $that->getPieceByPosition($king->getMove()->position->next);
                $that->detach($capturedPiece);
                return $that->kingIsMoved($king);
                break;
        }
    }

    /**
     * Castles the king.
     *
     * @param PGNChess\Piece\King $king
     *
     * @return boolean true if the castling is successfully run; otherwise false.
     */
    private function castle(King $king)
    {
        try
        {
            // get castling rook
            $rook = $king->getCastlingRook(iterator_to_array($this, false));
            // move king
            $kingsNewPosition = $king->getPosition();
            $kingsNewPosition->current = PGN::castling($king->getColor())->{PGN::PIECE_KING}->{$king->getMove()->type}->position->next;
            $king->setPosition($kingsNewPosition);
            $this->pieceIsMoved($king);
            // move rook
            $rooksNewPosition = $rook->getPosition();
            $rooksNewPosition->current = PGN::castling($king->getColor())->{PGN::PIECE_ROOK}->{$king->getMove()->type}->position->next;
            $rook->setPosition($rooksNewPosition);
            $this->pieceIsMoved($rook);
            // update board's status
            $this->status->castled->{$king->getColor()} = true;
        }
        catch (\Exception $e)
        {
            // TODO log exception...
            return false;
        }
        return true;
    }

    /**
     * Moves a piece.
     *
     * @param PGNChess\Piece\Piece $piece
     *
     * @return boolean true if the move is successfully performed; otherwise false
     */
    private function pieceIsMoved(Piece $piece)
    {
        try
        {
            // move piece
            $pieceClass = new \ReflectionClass(get_class($piece));
            $this->detach($piece);
            $this->attach($pieceClass->newInstanceArgs([
                $piece->getColor(),
                $piece->getMove()->position->next])
            );
            // remove the captured piece (if any) from the board
            if($piece->getMove()->isCapture)
            {
                $capturedPiece = $this->getPieceByPosition($piece->getMove()->position->next);
                $this->detach($capturedPiece);
            }
            $this->updateStatus();
        }
        catch (\Exception $e)
        {
            // TODO log exception...
            return false;
        }
        return true;
    }

    /**
     * Gets all pieces by color.
     *
     * @param string $color
     *
     * @return array
     */
    public function getPiecesByColor($color)
    {
        $pieces = [];
        $this->rewind();
        while ($this->valid())
        {
            $piece = $this->current();
            $piece->getColor() === $color ? $pieces[] = $piece : false;
            $this->next();
        }
        return $pieces;
    }

    /**
     * Gets the first piece on the board meeting the searching criteria.
     *
     * @param string $color
     * @param string $identity
     * @return PGNChess\Piece
     */
    public function getPiece($color, $identity)
    {
        $this->rewind();
        while ($this->valid())
        {
            $piece = $this->current();
            if ($piece->getColor() === $color && $piece->getIdentity() === $identity)
            {
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
     *
     * @return PGNChess\Piece
     */
    public function getPieceByPosition($square)
    {
        $this->rewind();
        while ($this->valid())
        {
            $piece = $this->current();
            if ($piece->getPosition()->current === $square)
            {
                return $piece;
            }
            $this->next();
        }
        return null;
    }

   /**
    * Builds an object containing the squares currently being controlled by both players.
    * This corresponds with the idea of space in chess. And more specifically, it is
    * helpful to decide whether or not a king can be put on this or that square of the board.
    *
    * @see Board::KingIsMoved(King $king)
    *
    * @return stdClass
    */
    private function space()
    {
        $space = (object) [
            PGN::COLOR_WHITE => [],
            PGN::COLOR_BLACK => []
        ];
        $this->rewind();
        while ($this->valid())
        {
            $piece = $this->current();
            switch($piece->getIdentity())
            {
                case 'K':
                    $space->{$piece->getColor()} = array_unique(
                        array_merge(
                            $space->{$piece->getColor()},
                            array_values(
                                array_intersect(
                                    array_values((array)$piece->getPosition()->scope),
                                    $this->status->squares->free
                                )
                            )
                        )
                    );
                    break;

                case 'P':
                    $space->{$piece->getColor()} = array_unique(
                        array_merge(
                            $space->{$piece->getColor()},
                            array_diff(
                                $piece->getPosition()->capture,
                                $this->status->squares->used->{$piece->getOppositeColor()}
                            )
                        )
                    );
                    break;

                default:
                    $space->{$piece->getColor()} = array_unique(
                        array_merge(
                            $space->{$piece->getColor()},
                            array_diff(
                                $piece->getLegalMoves(),
                                $this->status->squares->used->{$piece->getOppositeColor()}
                            )
                        )
                    );
                    break;
            }
            $this->next();
        }
        sort($space->{PGN::COLOR_WHITE});
        sort($space->{PGN::COLOR_BLACK});
        return $space;
    }

    /**
     * Builds an object containing the squares currently being attacked by both players.
     *
     * @return stdClass
     */
    private function attack()
    {
        $attack = (object) [
            PGN::COLOR_WHITE => [],
            PGN::COLOR_BLACK => []
        ];
        $this->rewind();
        while ($this->valid())
        {
            $piece = $this->current();
            switch($piece->getIdentity())
            {
                case 'K':
                    $attack->{$piece->getColor()} = array_unique(
                        array_merge(
                            $attack->{$piece->getColor()},
                            array_values(
                                array_intersect(
                                    array_values((array)$piece->getPosition()->scope),
                                    $this->status->squares->used->{$piece->getOppositeColor()}
                                )
                            )
                        )
                    );
                    break;

                case 'P':
                    $attack->{$piece->getColor()} = array_unique(
                        array_merge(
                            $attack->{$piece->getColor()},
                            array_intersect(
                                $piece->getPosition()->capture,
                                $this->status->squares->used->{$piece->getOppositeColor()}
                            )
                        )
                    );
                    break;

                default:
                    $attack->{$piece->getColor()} = array_unique(
                        array_merge(
                            $attack->{$piece->getColor()},
                            array_intersect(
                                $piece->getLegalMoves(),
                                $this->status->squares->used->{$piece->getOppositeColor()}
                            )
                        )
                    );
                    break;
            }
            $this->next();
        }
        sort($attack->{PGN::COLOR_WHITE});
        sort($attack->{PGN::COLOR_BLACK});
        return $attack;
    }

    /**
     * Verifies whether or not a piece's move leaves the board in check.
     *
     * @param PGNChess\Piece $piece
     *
     * @return boolean
     */
    private function isCheck($piece)
    {
        $that = $this;
        $that->pieceIsMoved($piece);
        $king = $that->getPiece($piece->getColor(), PGN::PIECE_KING);
        if (in_array($king->getPosition()->current, $that->attack()->{$king->getOppositeColor()}))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
