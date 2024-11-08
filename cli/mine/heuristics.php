<?php

namespace ChessData\Cli\Calc;

require_once __DIR__ . '/../../vendor/autoload.php';

use Chess\SanHeuristic;
use Chess\Function\FastFunction;
use ChessData\Pdo;
use Dotenv\Dotenv;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Heuristics extends CLI
{
    protected $pdo;

    protected $table = 'games';

    public function __construct()
    {
        parent::__construct(true);

        $dotenv = Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();

        $conf = include(__DIR__ . '/../../config/database.php');

        $this->pdo = Pdo::getInstance($conf);
    }

    protected function setup(Options $options)
    {
        $options->setHelp('Apply analytics to mine for heuristics insights.');
    }

    protected function main(Options $options)
    {
        $fastFunction = new FastFunction();

        $sql = 'SELECT * FROM games';

        $rows = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $value = [];

            foreach ($fastFunction->names() as $name) {
                $value[] = (new SanHeuristic(
                    $fastFunction,
                    $name,
                    $row['movetext']
                ))->getBalance();
            }

            $sql = "UPDATE {$this->table} SET heuristics = :heuristics WHERE movetext = :movetext";

            $values = [
                [
                    'param' => ':heuristics',
                    'value' => json_encode($value, true),
                    'type' => \PDO::PARAM_STR,
                ],
                [
                    'param' => ':movetext',
                    'value' => $row['movetext'],
                    'type' => \PDO::PARAM_STR,
                ],
            ];

            try {
                $this->pdo->query($sql, $values);
            } catch (\Exception $e) {}
        }
    }
}

$cli = new Heuristics();
$cli->run();
