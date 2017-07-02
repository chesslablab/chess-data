<?php
namespace PGNChess\Piece;

use PGNChess\PGN;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\King;

/**
 * Class that represents a rook.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Rook extends Slider
{
    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     */
    public function __construct($color, $square)
    {
        parent::__construct($color, $square, PGN::PIECE_ROOK);
        $this->position->scope = (object)[
            'up' => [],
            'bottom' => [],
            'left' => [],
            'right' => []
        ];
        $this->scope();
    }

    /**
     * Calculates the rook's scope.
     */
    protected function scope()
    {
        try // up
        {
            $file = $this->position->current[0];
            $rank = (int)$this->position->current[1] + 1;
            while (PGN::square($file.$rank, true))
            {
                $this->position->scope->up[] = $file . $rank;
                $rank = (int)$rank + 1;
            }
        }
        catch (\InvalidArgumentException $e) {}

        try // down
        {
            $file = $this->position->current[0];
            $rank = (int)$this->position->current[1] - 1;
            while (PGN::square($file.$rank, true))
            {
                $this->position->scope->bottom[] = $file . $rank;
                $rank = (int)$rank - 1;
            }
        }
        catch (\InvalidArgumentException $e) {}

        try // left
        {
            $file = chr(ord($this->position->current[0]) - 1);
            $rank = (int)$this->position->current[1];
            while (PGN::square($file.$rank, true))
            {
                $this->position->scope->left[] = $file . $rank;
                $file = chr(ord($file) - 1);
            }
        }
        catch (\InvalidArgumentException $e) {}

        try // right
        {
            $file = chr(ord($this->position->current[0]) + 1);
            $rank = (int)$this->position->current[1];
            while (PGN::square($file.$rank, true))
            {
                $this->position->scope->right[] = $file . $rank;
                $file = chr(ord($file) + 1);
            }
        }
        catch (\InvalidArgumentException $e) {}
    }

    /**
     * Updates the king's castling status.
     *
     * @param King
     */
    public function updateCastling(King &$king)
    {
        if (!$king->getCastling()->isCastled)
        {
            $this->getPosition()->current === PGN::castling($this->getColor())
                    ->{PGN::PIECE_ROOK}
                    ->{PGN::CASTLING_SHORT}
                    ->position
                    ->current
                ? $king->forbidCastling(PGN::CASTLING_SHORT)
                : false;

            $this->getPosition()->current === PGN::castling($this->getColor())
                    ->{PGN::PIECE_ROOK}
                    ->{PGN::CASTLING_LONG}
                    ->position
                    ->current
                ? $king->forbidCastling(PGN::CASTLING_LONG)
                : false;
        }
    }
}
