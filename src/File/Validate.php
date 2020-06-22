<?php

namespace PGNChessData\File;

use PGNChess\Exception\UnknownNotationException;
use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate as PgnValidate;

class Validate extends AbstractFile
{
    private $result = [];

    public function __construct(string $filepath)
    {
        parent::__construct($filepath);

        $this->result = (object) [
            'valid' => 0,
            'errors' => []
        ];
    }

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
                        $this->result->errors[] = [
                            'tags' => array_filter($tags),
                        ];
                    }
                    $tags = [];
                    $movetext = '';
                } elseif ($this->line->startsMovetext($line)) {
                    if (PgnValidate::tags($tags)) {
                        $movetext .= ' ' . $line;
                    }
                } elseif ($this->line->endsMovetext($line)) {
                    $movetext .= ' ' . $line;
                    if (PgnValidate::movetext($movetext)) {
                        $this->result->valid++;
                    } else {
                        $this->result->errors[] = [
                            'tags' => array_filter($tags),
                        ];
                    }
                    $tags = [];
                    $movetext = '';
                } else {
                    $movetext .= ' ' . $line;
                }
            }
        }

        return $this->result;
    }
}
