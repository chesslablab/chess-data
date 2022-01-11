<?php

namespace ChessData\Seeder;

use Chess\PGN\Tag;
use ChessData\Pdo;

class Basic extends AbstractSeeder
{
    protected function insert(array $tags, string $movetext)
    {
        $values = [];
        $params = '';
        $sql = 'INSERT INTO games (';

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
        } catch (\Exception $e) {}

        return false;
    }
}
