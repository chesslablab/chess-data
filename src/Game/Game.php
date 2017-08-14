<?php
namespace PGNChess\Game;

use DeepCopy\DeepCopy;
use PGNChess\Game\Board;
use PGNChess\PGN\Converter;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validator;

class Game
{
    private $board;

    private $checked;

    private $mated;

    public function __construct()
    {
        $this->board = new Board;

        $this->checked = (object) [
            Symbol::WHITE => false,
            Symbol::BLACK => false
        ];

        $this->mated = (object) [
            Symbol::WHITE => false,
            Symbol::BLACK => false
        ];
    }

    public function play($move)
    {
        $this->board = $this->board->replicate(); // workaround for deep clones to work

        $result = $this->board->play($move);

        $this->checked->{$this->board->getStatus()->turn} = Analyzer::check($this->board);
        $this->mated->{$this->board->getStatus()->turn} = Analyzer::mate($this->board);

        return $result;
    }

    public function isChecked($color)
    {
        return $this->checked->{Validator::color($color)};
    }

    public function isMated($color)
    {
        return $this->mated->{Validator::color($color)};
    }

    public function getBoard()
    {
        return $this->board;
    }
}
