<?php
namespace PGNChess;

use PGNChess\PGN;
use PGNChess\Piece\Piece;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;

class Board extends \SplObjectStorage
{
    private $status;

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
            'turn' => PGN::COLOR_WHITE,
            'squares' => (object) [
                'used' => (object) [
                    PGN::COLOR_WHITE => [],
                    PGN::COLOR_BLACK => []
                ]
            ],
            'castled' => (object) [
                PGN::COLOR_WHITE => false,
                PGN::COLOR_BLACK => false
            ]
        ];

        $this->updateStatus();
    }

    public function getStatus()
    {
        return $this->status;
    }

    private function updateStatus()
    {
        // update the user's turn
        $this->status->turn === PGN::COLOR_WHITE ? PGN::COLOR_BLACK : PGN::COLOR_WHITE;
        // update squares used on the board
        $this->status->squares->used->{PGN::COLOR_WHITE} = [];
        $this->status->squares->used->{PGN::COLOR_BLACK} = [];
        $this->rewind();
        while ($this->valid())
        {
            $piece = $this->current();
            $this->status->squares->used->{$piece->getColor()}[] = $piece->getPosition()->current;
            $this->next();
        }
        return $this;
    }

    private function castle(King $king)
    {
        try
        {
            // prepare castling
            $rook = $this->getCastlingRook($king);
            $kingMoved = clone $king;
            $rookMoved = clone $rook;
            // move king
            $kingsNewPosition = $king->getPosition();
            $kingsNewPosition->current = $king->getCastlingInfo()->{PGN::PIECE_KING}->{$king->getNextMove()->type}->move->next;
            $kingMoved->setPosition($kingsNewPosition);
            $this->swap($kingMoved, $king);
            // move rook
            $rooksNewPosition = $rook->getPosition();
            $rooksNewPosition->current = $king->getCastlingInfo()->{PGN::PIECE_ROOK}->{$king->getNextMove()->type}->move->next;
            $rookMoved->setPosition($rooksNewPosition);
            $this->swap($rookMoved, $rook);
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

    private function move(Piece $piece)
    {
        try
        {
            $pieceMoved = clone $piece;
            $newPosition = $piece->getPosition();
            $newPosition->current = $piece->getNextMove()->position->next;
            $pieceMoved->setPosition($newPosition);
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

    private function swap(Piece $a, Piece $b)
    {
        $this->detach($b);
        $this->attach($a);
        return $this;
    }

    public function play(\stdClass $move)
    {
    	$piece = $this->pickPieceToMove($move);
        if ($this->isMovable($piece))
        {
            return $this->move($piece);
        }
        else if($this->isCastleable($piece))
        {
    		return $this->castle($piece);
    	}
        else
    	{
    		return false;
    	}
    }

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

    private function pickPieceToMove(\stdClass $move)
    {
        $pieces = $this->getPiecesByColor($move->color);
        $found = null;
        foreach($pieces as $piece)
        {
            if ($piece->getIdentity() === $move->identity)
            {
                // prioritize the matching of the less ambiguous piece according to the PGN format
                switch(true)
                {
                    case $move->type === PGN::MOVE_TYPE_KING_CASTLING_LONG:
                        $piece->setNextMove($move);
                        return $piece;
                        break;

                    case $move->type === PGN::MOVE_TYPE_KING_CASTLING_SHORT:
                        $piece->setNextMove($move);
                        return $piece;
                        break;

                    // is this a disambiguation move? For example, Rbe8, Q7g7. If so,
                    // the piece is obtained from the board by looking at its current
                    // position on it.
                    case preg_match("/{$move->position->current}/", $piece->getPosition()->current):
                        $piece->setNextMove($move);
                        return $piece;
                        break;

                    // otherwise, this is a usual move such as Nxd2, Nd2. This means
                    // that the current piece can be obtained from the board without
                    // specifying its current position.
                    default:
                        $piece->setNextMove($move);
                        $found = $piece;
                        break;
                }
            }
        }
        return $found;
    }

    /**
     * Gets the legal moves that can be performed on the board by any piece.
     *
     * These moves are computed by considering the piece itself and the
     * next move that the player wants to carry out. Specifically, the present
     * implementation for computing legal moves uses the concept of scope.
     *
     * A piece's scope represents all the squares under its control on an empty board.
     * Therefore, roughly speaking, the legal moves that such piece can perform is
     * nothing but a subtraction between its scope and the squares used by
     * both players.
     *
     * This idea is specially relevant for calculating the moves of R (Rook),
     * B (Bishop), and Q (Queen).
     *
     * @param Piece $piece
     * @param stdClass $move
     *
     * @return array The legal moves that the given $piece can perform.
     */
    private function getLegalMoves(Piece $piece)
    {
        $legalMoves = [];

        switch(true)
        {
            // TODO Add check constraint...
            case $piece->getNextMove()->type == PGN::MOVE_TYPE_KING:
                break;

            // BRQ moves and captures
            case $piece->getNextMove()->type == PGN::MOVE_TYPE_PIECE || $piece->getNextMove()->type == PGN::MOVE_TYPE_PIECE_CAPTURES:
                $scope = $piece->getPosition()->scope;
                foreach($scope as $walk)
                {
                    foreach($walk as $square)
                    {
                        if (
                            !in_array($square, $this->status->squares->used->{$piece->getColor()}) &&
                            !in_array($square, $this->status->squares->used->{$piece->getOppositeColor()})
                        )
                        {
                            $legalMoves[] = $square;
                        }
                        elseif (in_array($square, $this->status->squares->used->{$piece->getOppositeColor()}))
                        {
                            $legalMoves[] = $square;
                            break 1;
                        }
                        elseif (in_array($square, $this->status->squares->used->{$piece->getColor()}))
                        {
                            break 1;
                        }
                    }
                }
                break;

            case $piece->getNextMove()->type == PGN::MOVE_TYPE_KNIGHT:
                $scope = $piece->getPosition()->scope;
                foreach($scope->jumps as $square)
                {
                    if (
                        !in_array($square, $this->status->squares->used->{$piece->getColor()}) &&
                        !in_array($square, $this->status->squares->used->{$piece->getOppositeColor()})
                    )
                    {
                        $legalMoves[] = $square;
                    }
                    elseif (in_array($square, $this->status->squares->used->{$piece->getOppositeColor()}))
                    {
                        $legalMoves[] = $square;
                    }
                }
                break;

            case $piece->getNextMove()->type == PGN::MOVE_TYPE_PAWN:
                $scope = $piece->getPosition()->scope;
                foreach($scope->up as $square)
                {
                    if (
                        !in_array($square, $this->status->squares->used->{$piece->getColor()}) &&
                        !in_array($square, $this->status->squares->used->{$piece->getOppositeColor()})
                    )
                    {
                        $legalMoves[] = $square;
                    }
                    else
                    {
                        break;
                    }
                }
                break;

            // TODO Add check constraint...
            case $piece->getNextMove()->type == PGN::MOVE_TYPE_KING_CASTLING_LONG:
                $castlingInfo = $piece->getCastlingInfo();
                if (
                    !in_array($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->b, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->b, $this->status->squares->used->{$piece->getOppositeColor()}) &&
                    !in_array($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->c, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->c, $this->status->squares->used->{$piece->getOppositeColor()}) &&
                    !in_array($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->d, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_LONG}->freeSquares->d, $this->status->squares->used->{$piece->getOppositeColor()})
                )
                {
                    $legalMoves[] = $piece->getNextMove()->position; // this is PGN::CASTLING_LONG
                }
                break;

            // TODO Add check constraint...
            case $piece->getNextMove()->type == PGN::MOVE_TYPE_KING_CASTLING_SHORT:
                $castlingInfo = $piece->getCastlingInfo();
                if (
                    !in_array($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->freeSquares->f, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->freeSquares->f, $this->status->squares->used->{$piece->getOppositeColor()}) &&
                    !in_array($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->freeSquares->g, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castlingInfo->{PGN::PIECE_KING}->{PGN::CASTLING_SHORT}->freeSquares->g, $this->status->squares->used->{$piece->getOppositeColor()})
                )
                {
                    $legalMoves[] = $piece->getNextMove()->position; // this is PGN::CASTLING_SHORT
                }
                break;

            case $piece->getNextMove()->type == PGN::MOVE_TYPE_KING_CAPTURES:
                break;

            case $piece->getNextMove()->type == PGN::MOVE_TYPE_KNIGHT_CAPTURES:
                $scope = $piece->getPosition()->scope;
                foreach($scope->jumps as $square)
                {
                    if (in_array($square, $this->status->squares->used->{$piece->getOppositeColor()}))
                    {
                        $legalMoves[] = $square;
                    }
                }
                break;

            case $piece->getNextMove()->type == PGN::MOVE_TYPE_PAWN_CAPTURES:
                $capture = $piece->getPosition()->capture;
                foreach($capture as $square)
                {
                    if (in_array($square, $this->status->squares->used->{$piece->getOppositeColor()}))
                    {
                        $legalMoves[] = $square;
                    }
                }
                break;
        }

        return $legalMoves;
    }

    // TODO Add check constraint...
    private function isCastleable(Piece $piece)
    {
        if (
            ($piece->getNextMove()->type === PGN::CASTLING_LONG || $piece->getNextMove()->type === PGN::CASTLING_SHORT) &&
            !empty($this->getCastlingRook($piece)) &&
            in_array($piece->getNextMove()->position, $this->getLegalMoves($piece))
            )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function isMovable(Piece $piece)
    {
        if (
            $piece->getNextMove()->type !== PGN::CASTLING_LONG &&
            $piece->getNextMove()->type !== PGN::CASTLING_SHORT &&
            in_array($piece->getNextMove()->position->next, $this->getLegalMoves($piece))
            )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function getCastlingRook(King $king)
    {
        $this->rewind();
        while ($this->valid())
        {
            $piece = $this->current();
            if (
                $piece->getIdentity() === PGN::PIECE_ROOK &&
                $piece->getPosition()->current === $king->getCastlingInfo()->{PGN::PIECE_ROOK}->{$king->getNextMove()->type}->move->current
            )
            {
                return $piece;
            }
            $this->next();
        }
        return null;
    }

}
