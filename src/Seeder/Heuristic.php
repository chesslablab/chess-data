<?php

namespace PGNChessData\Seeder;

use PGNChess\Heuristic\AttackSnapshot;
use PGNChess\Heuristic\CenterSnapshot;
use PGNChess\Heuristic\CheckSnapshot;
use PGNChess\Heuristic\ConnectivitySnapshot;
use PGNChess\Heuristic\KingSafetySnapshot;
use PGNChess\Heuristic\MaterialSnapshot;
use PGNChess\Heuristic\SpaceSnapshot;
use PGNChess\ML\Supervised\Regression\Labeller\PrimesSnapshot as PrimesLabellerSnapshot;
use PGNChess\PGN\Tag;
use PGNChessData\Pdo;

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
                `attack`,
                `center`,
                `check`,
                `connectivity`,
                `king_safety`,
                `material`,
                `space`,
                `label`) VALUES
                ($params:movetext,
                :attack,
                :center,
                :check,
                :connectivity,
                :king_safety,
                :material,
                :space,
                :label)";

        array_push($values,
            [
                'param' => ':movetext',
                'value' => $movetext,
                'type' => \PDO::PARAM_STR,
            ],
            [
                'param' => ':attack',
                'value' => json_encode((new AttackSnapshot($movetext))->take()),
                'type' => \PDO::PARAM_STR,
            ],
            [
                'param' => ':center',
                'value' => json_encode((new CenterSnapshot($movetext))->take()),
                'type' => \PDO::PARAM_STR,
            ],
            [
                'param' => ':check',
                'value' => json_encode((new CheckSnapshot($movetext))->take()),
                'type' => \PDO::PARAM_STR,
            ],
            [
                'param' => ':connectivity',
                'value' => json_encode((new ConnectivitySnapshot($movetext))->take()),
                'type' => \PDO::PARAM_STR,
            ],
            [
                'param' => ':king_safety',
                'value' => json_encode((new KingSafetySnapshot($movetext))->take()),
                'type' => \PDO::PARAM_STR,
            ],
            [
                'param' => ':material',
                'value' => json_encode((new MaterialSnapshot($movetext))->take()),
                'type' => \PDO::PARAM_STR,
            ],
            [
                'param' => ':space',
                'value' => json_encode((new SpaceSnapshot($movetext))->take()),
                'type' => \PDO::PARAM_STR,
            ],
            [
                'param' => ':label',
                'value' => json_encode((new PrimesLabellerSnapshot($movetext))->take()),
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
