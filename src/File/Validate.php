<?php

namespace PGNChessData\File;

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
        $movetext = '';
        if ($file = fopen($this->filepath, 'r')) {
            while (!feof($file)) {
                $line = preg_replace('~[[:cntrl:]]~', '', fgets($file));
                try {
                    $tag = PgnValidate::tag($line);
                    $tags[$tag->name] = $tag->value;
                } catch (\Exception $e) {
                    if (!PgnValidate::tags($tags) && $this->line->startsMovetext($line)) {
                        $this->result->errors[] = ['tags' => array_filter($tags)];
                        $tags = [];
                        $movetext = '';
                    } elseif (PgnValidate::tags($tags) &&
                        (($this->line->isMovetext($line) || $this->line->endsMovetext($line)))
                    ) {
                        $movetext .= ' ' . $line;
                        !PgnValidate::movetext($movetext)
                            ? $this->result->errors[] = [
                                'tags' => array_filter($tags),
                                'movetext' => trim($movetext)]
                            : $this->result->valid += 1;
                        $tags = [];
                        $movetext = '';
                    } elseif (PgnValidate::tags($tags)) {
                        $movetext .= ' ' . $line;
                    }
                }
            }
            fclose($file);
        }

        return $this->result;
    }
}
