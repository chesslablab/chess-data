<?php

namespace PGNChessData\Validator;

use PGNChess\Exception\UnknownNotationException;
use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate as PgnValidate;
use PGNChessData\File\AbstractFile;

class Syntax extends AbstractFile
{
    public function syntax(): \stdClass
    {
        $tags = [];
        $movetext = '';
        $file = new \SplFileObject($this->filepath);
        while (!$file->eof()) {
            $line = rtrim($file->fgets());
            try {
                $tag = PgnValidate::tag($line);
                $tags[$tag->name] = $tag->value;
            } catch (UnknownNotationException $e) {
                if ($this->line->isOneLinerMovetext($line)) {
                    if (PgnValidate::tags($tags) && PgnValidate::movetext($line)) {
                        $this->result->valid++;
                    } else {
                        echo $this->printTags($tags);
                    }
                    $tags = [];
                    $movetext = '';
                    $this->result->total++;
                } elseif ($this->line->startsMovetext($line)) {
                    if (PgnValidate::tags($tags)) {
                        $movetext .= ' ' . $line;
                    }
                } elseif ($this->line->endsMovetext($line)) {
                    $movetext .= ' ' . $line;
                    if (PgnValidate::movetext($movetext)) {
                        $this->result->valid++;
                    } else {
                        echo $this->printTags($tags);
                    }
                    $tags = [];
                    $movetext = '';
                    $this->result->total++;
                } else {
                    $movetext .= ' ' . $line;
                }
            }
        }

        return $this->result;
    }

    protected function printTags($tags): string
    {
        $txt = '';
        foreach (array_filter($tags) as $key => $val) {
            $txt .= "$key: $val" . PHP_EOL;
        }

        return $txt . PHP_EOL;
    }
}
