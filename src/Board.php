<?php
namespace PGNChess;

use PGNChess\PGN;
use PGNChess\Squares;
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
        // compute squares statistics
        $this->status->squares = Squares::stats(iterator_to_array($this, false));
        // send square statistics to all pieces
        $this->rewind();
        while ($this->valid())
        {
            $piece = $this->current();
            $piece->setSquares($this->status->squares);
            $this->next();
        }
    }

    /**
     * Picks a piece to be moved.
     *
     * @param stdClass $move
     *
     * @return use PGNChess\Piece\Piece
     */
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
                    case $move->type === PGN::MOVE_TYPE_KING_CASTLING_SHORT:
                        $piece->setNextMove($move);
                        return $piece;
                        break;

                    case $move->type === PGN::MOVE_TYPE_KING_CASTLING_LONG:
                        $piece->setNextMove($move);
                        return $piece;
                        break;

                    // is this a disambiguation move? For example, Rbe8, Q7g7
                    case preg_match("/{$move->position->current}/", $piece->getPosition()->current):
                        $piece->setNextMove($move);
                        return $piece;
                        break;

                    // otherwise, this is a usual move such as Nxd2 or Nd2
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
     * Runs a chess move on the board.
     *
     * @param stdClass $move
     *
     * @return boolean true if the move is successfully run; otherwise false
     */
    public function play(\stdClass $move)
    {
        $piece = $this->pickPieceToMove($move);
        if ($piece->isMovable())
        {
            if($piece->getNextMove() === PGN::CASTLING_SHORT || $piece->getNextMove() === PGN::CASTLING_LONG)
            {
                return $this->castle($piece);
            }
            else
            {
                return $this->move($piece);
            }
        }
        else
        {
            return false;
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
            $kingsNewPosition->current = $king->getNextMove()->position->{PGN::PIECE_KING}->{$king->getNextMove()->type}->move->next;
            $kingMoved->setPosition($kingsNewPosition);
            $this->swap($kingMoved, $king);
            // move rook
            $rooksNewPosition = $rook->getPosition();
            $rooksNewPosition->current = $king->getNextMove()->position->{PGN::PIECE_ROOK}->{$king->getNextMove()->type}->move->next;
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

    /**
     * Swaps piece $a with piece $b.
     * This method is actually used for moving the pieces of the board.
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
     * Gets from the board all pieces by color.
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
}
