<?php
namespace PGNChess\Piece;

use PGNChess\Exception\UnknownNotationException;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validate;
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
     * @var string
     */
    private $file;

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

        $this->file = $this->position->current[0];

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
     * Gets the pawn's file.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * Calculates the pawn's scope.
     */
    protected function scope()
    {
        // next rank
        try {
            if (Validate::square($this->file . $this->ranks->next, true)) {
                $this->position->scope->up[] = $this->file . $this->ranks->next;
            }
        } catch (UnknownNotationException $e) {

        }

        // two square advance
        if ($this->position->current[1] == 2 && $this->ranks->initial == 2) {
            $this->position->scope->up[] = $this->file . ($this->ranks->initial + 2);
        }
        elseif ($this->position->current[1] == 7 && $this->ranks->initial == 7) {
            $this->position->scope->up[] = $this->file . ($this->ranks->initial - 2);
        }

        // capture square
        try {
            $file = chr(ord($this->file) - 1);
            if (Validate::square($file.$this->ranks->next, true)) {
                $this->position->capture[] = $file . $this->ranks->next;
            }
        } catch (UnknownNotationException $e) {

        }

        // capture square
        try {
            $file = chr(ord($this->file) + 1);
            if (Validate::square($file.$this->ranks->next, true)) {
                $this->position->capture[] = $file . $this->ranks->next;
            }
        } catch (UnknownNotationException $e) {

        }
    }

    public function getLegalMoves()
    {
        $moves = [];

        // add up squares
        
        foreach($this->getPosition()->scope->up as $square) {
            if (in_array($square, self::$boardStatus->squares->free)) {
                $moves[] = $square;
            } else {
                break;
            }
        }

        // add capture squares
        
        foreach($this->getPosition()->capture as $square) {
            if (in_array($square, self::$boardStatus->squares->used->{$this->getOppositeColor()})) {
                $moves[] = $square;
            }
        }

        // en passant implementation
        
        if (isset(self::$boardStatus->previousMove) &&
            self::$boardStatus->previousMove->identity === Symbol::PAWN && 
            self::$boardStatus->previousMove->color === $this->getOppositeColor()) {
            
            switch ($this->getColor()) {

                case Symbol::WHITE:

                    if ((int)$this->position->current[1] === 5) {
                        
                        $captureSquare = 
                            self::$boardStatus->previousMove->position->next[0] . 
                            (self::$boardStatus->previousMove->position->next[1]+1);
                            
                        if (in_array($captureSquare, $this->position->capture)) {
                            $this->position->enPassantSquare = self::$boardStatus->previousMove->position->next;
                            $moves[] = $captureSquare;                            
                        }
                        
                    }

                    break;

                case Symbol::BLACK:

                    if ((int)$this->position->current[1] === 4) {
                        
                        $captureSquare = 
                            self::$boardStatus->previousMove->position->next[0] . 
                            (self::$boardStatus->previousMove->position->next[1]-1);
                        
                        if (in_array($captureSquare, $this->position->capture)) {
                            $this->position->enPassantSquare = self::$boardStatus->previousMove->position->next;
                            $moves[] = $captureSquare;                            
                        }

                    }

                    break;
                    
            }

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
        return isset($this->move->newIdentity) && (int)$this->getMove()->position->next[1] === $this->ranks->promotion;
    }
}
