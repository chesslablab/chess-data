<?php

namespace ChessData\Seeder;

use Chess\Heuristic\Picture\Weighted as WeightedHeuristicPicture;
use Chess\ML\Supervised\Regression\Labeller\Primes\Snapshot as PrimesLabellerSnapshot;
use Chess\PGN\Tag;
use ChessData\Pdo;

class Heuristic extends AbstractSeeder
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

        $sql .= "`movetext`,
                `heuristic_picture`) VALUES
                ($params:movetext,
                :heuristic_picture)";

        array_push($values,
            [
                'param' => ':movetext',
                'value' => $movetext,
                'type' => \PDO::PARAM_STR,
            ],
            [
                'param' => ':heuristic_picture',
                'value' => json_encode((new WeightedHeuristicPicture($movetext))->take()),
                'type' => \PDO::PARAM_STR,
            ],
        );

        try {
            return Pdo::getInstance()->query($sql, $values);
        } catch (\PDOException $e) {

        }

        return false;
    }
}
