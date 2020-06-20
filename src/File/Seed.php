<?php

namespace PGNChessData\File;

use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate;
use PGNChessData\Pdo;
use PGNChessData\Exception\PgnFileSyntaxException;

class Seed extends AbstractFile
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

    public function db(): \stdClass
    {
        $tags = [];
        $movetext = '';
        if ($file = fopen($this->filepath, 'r')) {
            while (!feof($file)) {
                $line = preg_replace('~[[:cntrl:]]~', '', fgets($file));
                try {
                    $tag = Validate::tag($line);
                    $tags[$tag->name] = $tag->value;
                } catch (\Exception $e) {
                    if ($this->line->startsMovetext($line) && !Validate::tags($tags)) {
                        $this->result->errors[] = ['tags' => array_filter($tags)];
                        $tags = [];
                        $movetext = '';
                    } elseif (($this->line->isMovetext($line) || $this->line->endsMovetext($line)) &&
                        Validate::tags($tags)
                    ) {
                        $movetext .= ' ' . $line;
                        $validMovetext = Validate::movetext($movetext);
                        if (!$validMovetext) {
                            $this->result->errors[] = [
                                'tags' => array_filter($tags),
                                'movetext' => $movetext
                            ];
                        } else {
                            try {
                                Pdo::getInstance()->query(
                                    $this->sql(),
                                    $this->values($tags, $validMovetext)
                                );
                                $this->result->valid++;
                            } catch (\Exception $e) {
                                $this->result->errors[] = [
                                    'tags' => array_filter($tags),
                                    'movetext' => $validMovetext
                                ];
                            }
                        }
                        $tags = [];
                        $movetext = '';
                    } elseif (Validate::tags($tags)) {
                        $movetext .= ' ' . $line;
                    }
                }
            }
            fclose($file);
        }

        return $this->result;
    }

    protected function sql(): string
    {
        $sql = 'INSERT INTO games (';

        foreach (Tag::all() as $key => $value) {
            $sql .= "$value, ";
        }

        $sql .= 'movetext) VALUES (';

        foreach (Tag::all() as $key => $value) {
            $sql .= ":$value, ";
        }

        $sql .= ':movetext)';

        return $sql;
    }

    protected function values(array $tags, string $movetext): array
    {
        $values = [];

        $tags = array_replace($this->nullTags(), $tags);

        foreach ($tags as $key => $value) {
            $values[] = [
                'param' => ":$key",
                'value' => $value,
                'type' => \PDO::PARAM_STR
            ];
        }

        $values[] = [
            'param' => ':movetext',
            'value' => trim($movetext),
            'type' => \PDO::PARAM_STR
        ];

        return $values;
    }

    protected function nullTags()
    {
        $nullTags = [];
        foreach (Tag::all() as $key => $value) {
            $nullTags[$value] = null;
        }

        return $nullTags;
    }
}
