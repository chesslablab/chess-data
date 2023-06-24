<?php

namespace ChessData\Seeder;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\PGN\Tag;
use Chess\Variant\Classical\PGN\Move;
use Chess\Movetext\SanMovetext;
use ChessData\Pdo;

class Seeder
{
    protected $pdo;

    protected $table;

    protected $filepath;

    protected $line;

    protected $result;

    public function __construct(Pdo $pdo, string $table, string $filepath)
    {
        $this->pdo = $pdo;

        $this->table = $table;

        $this->filepath = $filepath;

        $this->line = new FileLine;

        $this->result = (object) [
            'total' => 0,
            'valid' => 0,
        ];
    }

    public function getResult()
    {
        return $this->result;
    }

    public function seed(): Seeder
    {
        $tags = [];
        $movetext = '';
        $file = new \SplFileObject($this->filepath);
        $move = new Move();
        while (!$file->eof()) {
            $line = rtrim($file->fgets());
            try {
                $tag = Tag::validate($line);
                $tags[$tag->name] = $tag->value;
            } catch (UnknownNotationException $e) {
                if ($this->line->isOneLinerMovetext($line)) {
                    if (!array_diff(Tag::mandatory(), array_keys($tags)) &&
                        $validMovetext = (new SanMovetext($move, $line))
                            ->validate()
                    ) {
                        if ($this->insert($tags, $validMovetext)) {
                            $this->result->valid++;
                        }
                    }
                    $tags = [];
                    $movetext = '';
                    $this->result->total++;
                } elseif ($this->line->startsMovetext($line)) {
                    if (!array_diff(Tag::mandatory(), array_keys($tags))) {
                        $movetext .= ' ' . $line;
                    }
                } elseif ($this->line->endsMovetext($line)) {
                    $movetext .= ' ' . $line;
                    if ($validMovetext = (new SanMovetext($move, $movetext))->validate()) {
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

        return $this;
    }

    protected function insert(array $tags, string $movetext)
    {
        $values = [];
        $params = '';
        $sql = "INSERT INTO {$this->table} (";

        foreach (Tag::loadable() as $name) {
            if (isset($tags[$name])) {
                $values[] = [
                    'param' => ":$name",
                    'value' => $tags[$name],
                    'type' => \PDO::PARAM_STR
                ];
                $params .= ":$name, ";
                $sql .= "$name, ";
            }
        }

        $sql .= "movetext) VALUES ($params:movetext)";

        $values[] = [
            'param' => ':movetext',
            'value' => $movetext,
            'type' => \PDO::PARAM_STR
        ];

        try {
            return $this->pdo->query($sql, $values);
        } catch (\Exception $e) {

        }

        return false;
    }
}
