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
        $piece = $this->getPieceToMove($move);
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

    public function getPieceToMove($move)
    {
        $found = null;
        $this->rewind();
        while ($this->valid())
        {
            $piece = $this->current();
            // prioritize the matching of the less ambiguous piece according to the PGN format
            if (
                $piece->getColor() === $move->color &&
                $piece->getIdentity() === $move->identity &&
                preg_match("/{$move->position->current}/", $piece->getPosition()->current)
            )
            {
                return $piece;
            }
            // match any piece fulfilling the condition
            else if ($piece->getColor() === $move->color && $piece->getIdentity() === $move->identity)
            {
                $found = $piece;
            }
            $this->next();
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
                        else if (in_array($square, $this->status->squares->used->{$piece->getOppositeColor()}))
                        {
                            $legalMoves[] = $square;
                            break 1;
                        }
                        else if (in_array($square, $this->status->squares->used->{$piece->getColor()}))
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
                    else if (in_array($square, $this->status->squares->used->{$piece->getOppositeColor()}))
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

            case $move->type == PGN::MOVE_TYPE_LONG_CASTLING:
                break;

            case $move->type == PGN::MOVE_TYPE_SHORT_CASTLING:
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

            case  $move->type == PGN::MOVE_TYPE_PAWN_CAPTURES:
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
