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
        // update squares used
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

    private function setTurn($turn)
    {
        $this->status->turn = $turn;
    }

    public function castle(King $king, \stdClass $move)
    {
        switch($move->type)
        {
            case PGN::CASTLING_LONG:
                // TODO ...
                break;

            case PGN::CASTLING_SHORT:
                // TODO ...
                break;
        }

        $this->status->castled->{$king->getColor()}->true;
    }

    private function swap(Piece $a, Piece $b)
    {
        $this->detach($b);
        $this->attach($a);
        return $this;
    }

    // TODO now coding this method...
    public function move(\stdClass $move)
    {
        $piece = $this->pickPieceToMove($move);

        // print_r($piece); Exit;

        if ($this->canPieceBeMoved($piece))
        {
            // castling move
            if ($move->type === PGN::CASTLING_LONG || $move->type === PGN::CASTLING_SHORT)
            {
                $this->castle($piece, $move);
            }
            // non-castling move
            else
            {
                $pieceMoved = clone $piece;
                $position = $piece->getPosition();
                $position->current = $move->position->next;
                $pieceMoved->setPosition($position);
                $this->swap($pieceMoved, $piece)->updateStatus();
            }
            return true;
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
                        
                    // is it a disambiguation move? For example, Rbe8, Q7g7. If so,
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
     * Gets the legal moves that can be performed on the board by a  piece.
     *
     * These moves are computed by considering the piece itself and the
     * next move that the player wants to carry out.
     *
     * @param Piece $piece
     * @param stdClass $move
     *
     * @return array The legal moves that $piece can perform.
     */
    private function getLegalMoves(Piece $piece)
    {
        $legalMoves = [];

        switch(true)
        {
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
                    !in_array($castlingInfo->K->long->freeSquares->b, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castlingInfo->K->long->freeSquares->b, $this->status->squares->used->{$piece->getOppositeColor()}) &&
                    !in_array($castlingInfo->K->long->freeSquares->c, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castlingInfo->K->long->freeSquares->c, $this->status->squares->used->{$piece->getOppositeColor()}) &&
                    !in_array($castlingInfo->K->long->freeSquares->d, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castlingInfo->K->long->freeSquares->d, $this->status->squares->used->{$piece->getOppositeColor()})
                )
                {
                    $legalMoves[] = $piece->getNextMove()->position; // this is PGN::CASTLING_LONG
                }
                break;

            // TODO Add check constraint...
            case $piece->getNextMove()->type == PGN::MOVE_TYPE_KING_CASTLING_SHORT:
                $castlingInfo = $piece->getCastlingInfo();
                if (
                    !in_array($castlingInfo->K->short->freeSquares->f, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castlingInfo->K->short->freeSquares->f, $this->status->squares->used->{$piece->getOppositeColor()}) &&
                    !in_array($castlingInfo->K->short->freeSquares->g, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castlingInfo->K->short->freeSquares->g, $this->status->squares->used->{$piece->getOppositeColor()})
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

    public function canPieceBeMoved(Piece $piece)
    {
        $legalMoves = $this->getLegalMoves($piece);
        // castling move, remember: the position can't be objectized from the
        // PGN entry since it depends on the color, which means there's
        // no $move->position object
        if
        (
            ($piece->getNextMove()->position === PGN::CASTLING_LONG || $piece->getNextMove()->position === PGN::CASTLING_SHORT) &&
            !in_array($piece->getNextMove()->position, $legalMoves)
        )
        {
            return false;
        }
        // non-castling move
        elseif (!in_array($piece->getNextMove()->position->next, $legalMoves))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

}
