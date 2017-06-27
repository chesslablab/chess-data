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
     * @return PGNChess\Piece\Piece|null     The piece that corresponds to the move;
     *                                       otherwise null.
     *
     * @throws \InvalidArgumentException
     */
    private function pickPieceToMove(\stdClass $move)
    {
        $pieces = $this->getPiecesByColor($move->color);
        foreach ($pieces as $piece)
        {
            if ($piece->getIdentity() === $move->identity)
            {
                switch($piece->getIdentity())
                {
                    // the king is the only non-ambiguous piece
                    case PGN::PIECE_KING:
                        $piece->setMove($move);
                        return $piece;
                        break;
                    // try to disambiguate the move; for example, Rbe8, Q7g7
                    default:
                        if (preg_match("/{$move->position->current}/", $piece->getPosition()->current))
                        {
                            $piece->setMove($move);
                            return $piece;
                        }
                        break;
                }
            }
        }
        throw new \InvalidArgumentException("This piece does not exist on the board: {$move->color} {$move->identity} on {$move->position->current}");
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
        $piece = $this->pickPieceToMove($move);
        switch(true)
        {
            case $piece->isMovable() && $piece->getMove()->type === PGN::MOVE_TYPE_KING:
                return $this->kingIsMoved($piece);
                break;

            case $piece->isMovable() && $piece->getMove()->type === PGN::CASTLING_SHORT:
                return $this->castle($piece);
                break;

            case $piece->isMovable() && $piece->getMove()->type === PGN::CASTLING_LONG:
                return $this->castle($piece);
                break;

            case $piece->isMovable():
                return $this->pieceIsMoved($piece);
                break;

            default:
                return false;
                break;
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
            // prepare castling
            $rook = $king->getCastlingRook(iterator_to_array($this, false));
            $kingMoved = clone $king;
            $rookMoved = clone $rook;
            // move king
            $kingsNewPosition = $king->getPosition();
            $kingsNewPosition->current = PGN::castling($king->getColor())->{PGN::PIECE_KING}->{$king->getMove()->type}->position->next;
            $kingMoved->setPosition($kingsNewPosition);
            $this->swap($kingMoved, $king);
            // move rook
            $rooksNewPosition = $rook->getPosition();
            $rooksNewPosition->current = PGN::castling($king->getColor())->{PGN::PIECE_ROOK}->{$king->getMove()->type}->position->next;
            $rookMoved->setPosition($rooksNewPosition);
            $this->swap($rookMoved, $rook);
            // update board's status
            $this->status->castled->{$king->getColor()} = true;
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
            $pieceMoved = clone $piece;
            $newPosition = $piece->getPosition();
            $newPosition->current = $piece->getMove()->position->next;
            $pieceMoved->setPosition($newPosition);
            // $pieceMoved->setMove(null);
            $this->swap($pieceMoved, $piece);
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
     * Swaps piece $a with piece $b. This method is actually used for moving
     * the pieces of the board.
     *
     * @param Piece $a PGNChess\Piece
     * @param Piece $b PGNChess\Piece
     *
     * @return PGNChess\Board
     */
    private function swap(Piece $a, Piece $b)
    {
        $this->detach($b);
        $this->attach($a);
        return $this;
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
    * Builds an object containing the squares currently being controlled by
    * both players. This corresponds with the idea of space in chess. And more
    * specifically, it is helpful to decide whether or not a king can be put on
    * this or that square of the board.
    *
    * @see Board::KingIsMoved(King $king)
    *
    * @param $pieces
    *
    * @return stdClass
    */
    private function space()
    {
        $squares = (object) [
            PGN::COLOR_WHITE => [],
            PGN::COLOR_BLACK => []
        ];
        // first of all, compute the legal moves that can be made by non-king pieces
        $this->rewind();
        while ($this->valid())
        {
            $piece = $this->current();
            switch($piece->getIdentity())
            {
                case 'K':
                    // exclude king since this is the exception, do nothing
                    break;

                case 'P':
                    $squares->{$piece->getColor()} = array_unique(
                        array_merge(
                            $squares->{$piece->getColor()},
                            $piece->getPosition()->capture
                        )
                    );
                    break;

                default:
                    $squares->{$piece->getColor()} = array_unique(
                        array_merge(
                            $squares->{$piece->getColor()},
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
        // and finally add the squares controlled by kings
        $kings = [];
        $this->rewind();
        while ($this->valid())
        {
            $piece = $this->current();
            if($piece->getIdentity() === 'K')
            {
                $squares->{$piece->getColor()} = array_unique(
                    array_merge(
                        $squares->{$piece->getColor()},
                        array_values(
                            array_intersect(
                                array_values((array)$piece->getPosition()->scope),
                                $this->status->squares->free
                            )
                        )
                    )
                );
            }
            $this->next();
        }
        sort($squares->{PGN::COLOR_WHITE});
        sort($squares->{PGN::COLOR_BLACK});
        return $squares;
    }
}
