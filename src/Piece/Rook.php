<?php
namespace PGNChess\Piece;

use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validator;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Type\RookType;

/**
 * Rook class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Rook extends Slider
{
    /**
     * @var string
     */
    private $type;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     * @param string $castling
     * @throws \InvalidArgumentException
     */
    public function __construct($color, $square, $type)
    {
        if (!in_array($type, RookType::getChoices())) {
            throw new \InvalidArgumentException(
                "A valid rook type needs to be provided in order to instantiate a rook."
            );
        } else {
            $this->type = $type;
        }

        parent::__construct($color, $square, Symbol::ROOK);

        $this->position->scope = (object)[
            'up' => [],
            'bottom' => [],
            'left' => [],
            'right' => []
        ];

        $this->scope();
    }

    /**
     * Returns the rook's type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Calculates the rook's scope.
     */
    protected function scope()
    {
        // up
        try {
            $file = $this->position->current[0];
            $rank = (int)$this->position->current[1] + 1;
            while (Validator::square($file.$rank)) {
                $this->position->scope->up[] = $file . $rank;
                $rank = (int)$rank + 1;
            }
        } catch (\InvalidArgumentException $e) {

        }

        // down
        try {
            $file = $this->position->current[0];
            $rank = (int)$this->position->current[1] - 1;
            while (Validator::square($file.$rank)) {
                $this->position->scope->bottom[] = $file . $rank;
                $rank = (int)$rank - 1;
            }
        } catch (\InvalidArgumentException $e) {

        }

        // left
        try {
            $file = chr(ord($this->position->current[0]) - 1);
            $rank = (int)$this->position->current[1];
            while (Validator::square($file.$rank)) {
                $this->position->scope->left[] = $file . $rank;
                $file = chr(ord($file) - 1);
            }
        } catch (\InvalidArgumentException $e) {

        }

        // right
        try {
            $file = chr(ord($this->position->current[0]) + 1);
            $rank = (int)$this->position->current[1];
            while (Validator::square($file.$rank)) {
                $this->position->scope->right[] = $file . $rank;
                $file = chr(ord($file) + 1);
            }
        } catch (\InvalidArgumentException $e) {

        }
    }
}
