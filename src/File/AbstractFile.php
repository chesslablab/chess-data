<?php

namespace ChessData\File;

abstract class AbstractFile
{
    protected $filepath;

    protected $line;

    protected $result;

    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
        $this->line = new Line;
        $this->result = (object) [
            'total' => 0,
            'valid' => 0,
        ];
    }
}
