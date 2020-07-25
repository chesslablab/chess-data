<?php

namespace PGNChessData\File;

use PGNChess\PGN\Symbol;

class Line
{
    public function isOneLinerMovetext(string $line): bool
    {
        return $this->startsMovetext($line) && $this->endsMovetext($line);
    }

    public function startsMovetext(string $line): bool
    {
        return $this->startsWith($line, '1.');
    }

    public function endsMovetext(string $line): bool
    {
        return $this->endsWith($line, Symbol::RESULT_WHITE_WINS) ||
            $this->endsWith($line, Symbol::RESULT_BLACK_WINS) ||
            $this->endsWith($line, Symbol::RESULT_DRAW) ||
            $this->endsWith($line, Symbol::RESULT_UNKNOWN);
    }

    public function startsWith(string $haystack, string $needle): bool
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }

    public function endsWith(string $haystack, string $needle): bool
    {
        return substr($haystack, -strlen($needle)) === $needle;
    }
}
