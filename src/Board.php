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

        // compute square statistics
        $this->status->squares = Squares::stats(iterator_to_array($this, false));

        // send square statistics to all pieces
        $this->rewind();
        while ($this->valid())
        {
            $piece = $this->current();
            $piece->setSquares($this->status->squares);
            $this->next();
        }

        // forbid King's moves
        $this->forbidKingMoves();
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
                        $piece->setMove($move);
                        return $piece;
                        break;

                    case $move->type === PGN::MOVE_TYPE_KING_CASTLING_LONG:
                        $piece->setMove($move);
                        return $piece;
                        break;

                    // is this a disambiguation move? For example, Rbe8, Q7g7
                    case preg_match("/{$move->position->current}/", $piece->getPosition()->current):
                        $piece->setMove($move);
                        return $piece;
                        break;

                    // otherwise, this is a usual move such as Nxd2 or Nd2
                    default:
                        $piece->setMove($move);
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
            if($piece->getMove() === PGN::CASTLING_SHORT || $piece->getMove() === PGN::CASTLING_LONG)
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
            $kingsNewPosition->current = $king->getMove()->position->{PGN::PIECE_KING}->{$king->getMove()->type}->move->next;
            $kingMoved->setPosition($kingsNewPosition);
            $this->swap($kingMoved, $king);
            // move rook
            $rooksNewPosition = $rook->getPosition();
            $rooksNewPosition->current = $king->getMove()->position->{PGN::PIECE_ROOK}->{$king->getMove()->type}->move->next;
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
            $newPosition->current = $piece->getMove()->position->next;
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

    // TODO try to use setInfo instead of swap!
    public function forbidKingMoves()
    {
        $squares = (object) [
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
                    // do nothing...
                    break;

                case 'P':
                    $squares->{$piece->getOppositeColor()} = array_merge(
                        array_unique($squares->{$piece->getOppositeColor()}),
                        $piece->getPosition()->capture
                    );
                    break;

                default:
                    $squares->{$piece->getOppositeColor()} = array_merge(
                        array_unique($squares->{$piece->getOppositeColor()}),
                        $piece->getLegalMoves()
                    );
                    break;
            }
            $this->next();
        }

        sort($squares->{PGN::COLOR_WHITE});
        sort($squares->{PGN::COLOR_BLACK});

        $whiteKing = $this->getPiece(PGN::COLOR_WHITE, PGN::PIECE_KING);
        $blackKing = $this->getPiece(PGN::COLOR_BLACK, PGN::PIECE_KING);

        $whiteKingPosition = $whiteKing->getPosition();
        $blackKingPosition = $blackKing->getPosition();

        $squares->{PGN::COLOR_WHITE} = array_merge(
            $squares->{PGN::COLOR_WHITE},
            array_values((array)$blackKingPosition->scope)
        );

        $squares->{PGN::COLOR_BLACK} = array_merge(
            $squares->{PGN::COLOR_BLACK},
            array_values((array)$whiteKingPosition->scope)
        );

        $whiteKingPosition->forbidden = $squares->{PGN::COLOR_WHITE};
        $blackKingPosition->forbidden = $squares->{PGN::COLOR_BLACK};

        $updatedWhiteKing = clone $whiteKing;
        $updatedBlackKing = clone $blackKing;

        $updatedWhiteKing->setPosition($whiteKingPosition);
        $updatedBlackKing->setPosition($blackKingPosition);

        $this->swap($updatedWhiteKing, $whiteKing);
        $this->swap($updatedBlackKing, $blackKing);

    }
}
