<?php

namespace PGNChessData\File;

use PGNChess\Exception\UnknownNotationException;
use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate;
use PGNChessData\Pdo;

class Seed extends AbstractFile
{
    public function db(): \stdClass
    {
        $tags = [];
        $movetext = '';
        $file = new \SplFileObject($this->filepath);
        while (!$file->eof()) {
            $line = rtrim($file->fgets());
            try {
                $tag = Validate::tag($line);
                $tags[$tag->name] = $tag->value;
            } catch (UnknownNotationException $e) {
                if ($this->line->isOneLinerMovetext($line)) {
                    if (Validate::tags($tags) && $validMovetext = Validate::movetext($line)) {
                        if ($this->insert($tags, $validMovetext)) {
                            $this->result->valid++;
                        }
                    }
                    $tags = [];
                    $movetext = '';
                    $this->result->total++;
                } elseif ($this->line->startsMovetext($line)) {
                    if (Validate::tags($tags)) {
                        $movetext .= ' ' . $line;
                    }
                } elseif ($this->line->endsMovetext($line)) {
                    $movetext .= ' ' . $line;
                    if ($validMovetext = Validate::movetext($movetext)) {
                        if ($this->insert($tags, $validMovetext)) {
                            $this->result->valid++;
                        }
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

    protected function insert(array $tags, string $movetext)
    {
        $values = [];
        $params = '';
        $sql = 'INSERT INTO games (';

        foreach (Tag::mandatory() as $name) {
            $values[] = [
                'param' => ":$name",
                'value' => $tags[$name],
                'type' => \PDO::PARAM_STR
            ];
            $params .= ":$name, ";
            $sql .= "$name, ";
        }

        $sql .= "movetext) VALUES ($params:movetext)";

        $values[] = [
            'param' => ':movetext',
            'value' => $movetext,
            'type' => \PDO::PARAM_STR
        ];

        try {
            return Pdo::getInstance()->query($sql, $values);
        } catch (\PDOException $e) {

        }

        return false;
    }
}
