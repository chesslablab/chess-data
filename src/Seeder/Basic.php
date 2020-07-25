<?php

namespace PGNChessData\Seeder;

use PGNChess\PGN\Tag;
use PGNChessData\Pdo;

class Basic extends AbstractSeeder
{
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
