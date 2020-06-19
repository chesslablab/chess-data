<?php

namespace PGNChessData\File;

use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate as PgnValidate;
use PGNChessData\Pdo;
use PGNChessData\File\Validate as PgnFileValidate;
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
        $movetext = '';
        if ($file = fopen($this->filepath, 'r')) {
            while (!feof($file)) {
                $line = preg_replace('~[[:cntrl:]]~', '', fgets($file));
                try {
                    $tag = PgnValidate::tag($line);
                    $tags[$tag->name] = $tag->value;
                } catch (\Exception $e) {
                    if (!Tag::mandatory($tags) && $this->line->startsMovetext($line)) {
                        $this->result->errors[] = ['tags' => array_filter($tags)];
                        $tags = [];
                        $movetext = '';
                    } elseif (Tag::mandatory($tags) && (($this->line->isMovetext($line) || $this->line->endsMovetext($line)))) {
                        $movetext .= ' ' . $line;
                        if (!PgnValidate::movetext($movetext)) {
                            $this->result->errors[] = [
                                'tags' => array_filter($tags),
                                'movetext' => $movetext
                            ];
                        } else {
                            try {
                                Pdo::getInstance()->query(
                                    $this->sql(),
                                    $this->values(array_replace($this->nullTags(), $tags), $movetext)
                                );
                                $this->result->valid += 1;
                            } catch (\Exception $e) {
                                $this->result->errors[] = [
                                    'tags' => array_filter($tags),
                                    'movetext' => $movetext
                                ];
                            }
                        }
                        $tags = [];
                        $movetext = '';
                    } elseif (Tag::mandatory($tags)) {
                        $movetext .= ' ' . $line;
                    }
                }
            }
            fclose($file);
        }

        if (empty($this->result->errors)) {
            unset($this->result->errors);
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
