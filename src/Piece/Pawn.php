<?php
namespace PGNChess\Piece;

use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validator;
use PGNChess\Piece\AbstractPiece;

/**
 * Pawn class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Pawn extends AbstractPiece
{
    /**
     * @var array
     */
    private $ranks;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     */
    public function __construct($color, $square)
    {
        parent::__construct($color, $square, Symbol::PAWN);

        switch ($this->color) {
            case Symbol::WHITE:
                $this->ranks = (object) [
                    'initial' => 2,
                    'next' => (int)$this->position->current[1] + 1,
                    'promotion' => 8
                ];
                break;

            case Symbol::BLACK:
                $this->ranks = (object) [
                    'initial' => 7,
                    'next' => (int)$this->position->current[1] - 1,
                    'promotion' => 1
                ];
                break;
        }

        $this->position->capture = [];
        $this->position->scope = (object)[
            'up' => []
        ];

        $this->scope();
    }

    /**
     * Calculates the pawn's scope.
     */
    protected function scope()
    {
        // next rank
        try {
            $file = $this->position->current[0];
            if (Validator::square($file.$this->ranks->next, true)) {
                $this->position->scope->up[] = $file . $this->ranks->next;
            }
        } catch (\InvalidArgumentException $e) {

        }

        // two square advance
        if ($this->position->current[1] == 2 && $this->ranks->initial == 2) {
            $this->position->scope->up[] = $this->position->current[0] . ($this->ranks->initial + 2);
        }
        elseif ($this->position->current[1] == 7 && $this->ranks->initial == 7) {
            $this->position->scope->up[] = $this->position->current[0] . ($this->ranks->initial - 2);
        }

        // capture square
        try {
            $file = chr(ord($this->position->current[0]) - 1);
            if (Validator::square($file.$this->ranks->next, true)) {
                $this->position->capture[] = $file . $this->ranks->next;
            }
        } catch (\InvalidArgumentException $e) {

        }

        // capture square
        try {
            $file = chr(ord($this->position->current[0]) + 1);
            if (Validator::square($file.$this->ranks->next, true)) {
                $this->position->capture[] = $file . $this->ranks->next;
            }
        } catch (\InvalidArgumentException $e) {

        }
    }

    public function getLegalMoves()
    {
        $moves = [];

        // add up squares
        foreach($this->getPosition()->scope->up as $square) {
            if (in_array($square, self::$squares->free)) {
                $moves[] = $square;
            } else {
                break;
            }
        }

        // add capture squares
        foreach($this->getPosition()->capture as $square) {
            if (in_array($square, self::$squares->used->{$this->getOppositeColor()})) {
                $moves[] = $square;
            }
        }

        // add en passant squares
        if (
            $this->ranks->initial === 2 &&
            (int)$this->position->current[1] === 5 &&
            self::$previousMove->{$this->getOppositeColor()}->identity === Symbol::PAWN &&
            (self::$previousMove->{$this->getOppositeColor()}->position->next[0] .
            (self::$previousMove->{$this->getOppositeColor()}->position->next[1]+1) === $this->position->capture[0] ||
            (isset($this->position->capture[1]) &&
            (self::$previousMove->{$this->getOppositeColor()}->position->next[0] .
            (self::$previousMove->{$this->getOppositeColor()}->position->next[1]+1) === $this->position->capture[1])))
        ) {
            $moves[] =  self::$previousMove->{$this->getOppositeColor()}->position->next[0] .
                        (self::$previousMove->{$this->getOppositeColor()}->position->next[1]+1);
        } elseif (
            $this->ranks->initial === 7 &&
            (int)$this->position->current[1] === 4 &&
            self::$previousMove->{$this->getOppositeColor()}->identity === Symbol::PAWN &&
            (self::$previousMove->{$this->getOppositeColor()}->position->next[0] .
            (self::$previousMove->{$this->getOppositeColor()}->position->next[1]-1) === $this->position->capture[0] ||
            (isset($this->position->capture[1]) &&
            (self::$previousMove->{$this->getOppositeColor()}->position->next[0] .
            (self::$previousMove->{$this->getOppositeColor()}->position->next[1]-1) === $this->position->capture[1])))
        ) {
            $moves[] =  self::$previousMove->{$this->getOppositeColor()}->position->next[0] .
                        (self::$previousMove->{$this->getOppositeColor()}->position->next[1]-1);
        }

        return $moves;
    }

    /**
     * Checks whether the pawn is promoted.
     *
     * @return boolean
     */
    public function isPromoted()
    {
        if ((int)$this->getMove()->position->next[1] === $this->ranks->promotion) {
            return true;
        } else {
            return false;
        }
    }
}
