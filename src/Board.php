<?php
namespace PGNChess;

use PGNChess\PGN;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;

class Board extends \SplObjectStorage
{
    protected $status;

    public function __construct($pieces=null)
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

    public function movePiece($move)
    {
        // TODO now coding this part...
        $piece = $this->getPieceToBeMoved($move);
        if ($this->isLegalMove($piece, $move))
        {
            $this->detach($piece); // better swapping than detaching/attaching...
            $position = $piece->getPosition();
            $position->current = $move->position->next;
            $piece->setPosition($position);
            $this->attach($piece);
            // ...
            $this->setStatusSquares(); // don't forget to udpate the square list...
        }
        else // what if can't be moved?
        {

        }
        return $piece;
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

    public function getPieceToBeMoved($move)
    {
        $pieces = $this->getPiecesByColor($move->color);
        $found = null;
        // prioritize the matching of the less ambiguous piece according to the PGN format
        foreach($pieces as $piece)
        {
            // is the move a long castling?
            if (
                count($move) == 2 &&
                $move[0]->type == PGN::MOVE_TYPE_KING_CASTLING_LONG &&
                $move[1]->type == PGN::MOVE_TYPE_KING_CASTLING_LONG)
            {

            }
            // is it a short castling?
            elseif (
                count($move) == 2 &&
                $move[0]->type == PGN::MOVE_TYPE_KING_CASTLING_SHORT &&
                $move[1]->type == PGN::MOVE_TYPE_KING_CASTLING_SHORT)
            {

            }
            // is it a disambiguation move? For example, Rbe8, Q7g7. If so,
            // the piece is obtained from the board by looking at its current
            // position on it.
            elseif (
                $piece->getIdentity() === $move->identity &&
                preg_match("/{$move->position->current}/", $piece->getPosition()->current)
            )
            {
                return $piece;
            }
            // otherwise, this is a usual move such as Nxd2, Nd2. This means
            // that the current piece can be obtained from the board without specifying
            // its current position on the board.
            elseif ($piece->getIdentity() === $move->identity)
            {
                $found = $piece;
            }
        }
        return $found;
    }

    public function getLegalMoves($piece, $move)
    {
        $legalMoves = [];

        switch(true)
        {
            case $move->type == PGN::MOVE_TYPE_KING:
                break;

            // BRQ moves and captures
            case $move->type == PGN::MOVE_TYPE_PIECE || $move->type == PGN::MOVE_TYPE_PIECE_CAPTURES:
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

            case $move->type == PGN::MOVE_TYPE_KNIGHT:
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

            case $move->type == PGN::MOVE_TYPE_PAWN:
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
            case $move->type == PGN::MOVE_TYPE_KING_CASTLING_LONG:
                $castling = $piece->getCastling();
                if (
                    !in_array($castling->K->long->freeSquares->b, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castling->K->long->freeSquares->b, $this->status->squares->used->{$piece->getOppositeColor()}) &&
                    !in_array($castling->K->long->freeSquares->c, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castling->K->long->freeSquares->c, $this->status->squares->used->{$piece->getOppositeColor()}) &&
                    !in_array($castling->K->long->freeSquares->d, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castling->K->long->freeSquares->d, $this->status->squares->used->{$piece->getOppositeColor()})
                )
                {
                    $legalMoves[] = PGN::CASTLING_LONG;
                }
                break;

            // TODO Add check constraint...
            case $move->type == PGN::MOVE_TYPE_KING_CASTLING_SHORT:
                $castling = $piece->getCastling();
                if (
                    !in_array($castling->K->short->freeSquares->f, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castling->K->short->freeSquares->f, $this->status->squares->used->{$piece->getOppositeColor()}) &&
                    !in_array($castling->K->short->freeSquares->g, $this->status->squares->used->{$piece->getColor()}) &&
                    !in_array($castling->K->short->freeSquares->g, $this->status->squares->used->{$piece->getOppositeColor()})
                )
                {
                    $legalMoves[] = PGN::CASTLING_SHORT;
                }
                break;

            case $move->type == PGN::MOVE_TYPE_KING_CAPTURES:
                break;

            case $move->type == PGN::MOVE_TYPE_KNIGHT_CAPTURES:
                $scope = $piece->getPosition()->scope;
                foreach($scope->jumps as $square)
                {
                    if (in_array($square, $this->status->squares->used->{$piece->getOppositeColor()}))
                    {
                        $legalMoves[] = $square;
                    }
                }
                break;

            case $move->type == PGN::MOVE_TYPE_PAWN_CAPTURES:
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


    public function isLegalMove($piece, $move)
    {
        return in_array($move->position->next, $this->getLegalMoves($piece, $move));
    }

}
