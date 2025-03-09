<?php

namespace ChessData\Cli\Seed;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\Pdo;
use Dotenv\Dotenv;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Annotations extends CLI
{
    protected Pdo $pdo;

    protected string $table = 'annotations';

    public function __construct()
    {
        parent::__construct();

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $conf = include(__DIR__ . '/../../config/database.php');

        $this->pdo = Pdo::getInstance($conf);
    }

    protected function setup(Options $options)
    {
        $options->setHelp("Seeds the {$this->table} table.");
        $options->registerArgument('filepath', 'CSV file or folder with CSV files.', true);
    }

    protected function main(Options $options)
    {
        foreach (scandir($options->getArgs()[0]) as $item) {
            $this->seed($options->getArgs()[0] . "/$item");
        }
    }

    protected function seed(string $filepath)
    {
        if (is_file($filepath)) {
            $count = 0;
            $file = fopen($filepath, 'r');
            while (($line = fgetcsv($file)) !== false) {
                if ($count > 0) {
                    $sql = "INSERT INTO {$this->table} (
                        Event,
                        Round,
                        Site,
                        Date,
                        White,
                        Black,
                        Result,
                        WhiteElo,
                        BlackElo,
                        ECO,
                        movetext
                    ) VALUES (
                        :Event,
                        :Round,
                        :Site,
                        :Date,
                        :White,
                        :Black,
                        :Result,
                        :WhiteElo,
                        :BlackElo,
                        :ECO,
                        :movetext
                    )";
                    $values = [
                        [
                            'param' => ':Event',
                            'value' => $line[0],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':Round',
                            'value' => $line[1],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':Site',
                            'value' => $line[2],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':Date',
                            'value' => $line[3],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':White',
                            'value' => $line[4],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':Black',
                            'value' => $line[5],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':Result',
                            'value' => $line[6],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':WhiteElo',
                            'value' => $line[7],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':BlackElo',
                            'value' => $line[8],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':ECO',
                            'value' => $line[9],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':movetext',
                            'value' => $line[10],
                            'type' => \PDO::PARAM_STR,
                        ],
                    ];
                    try {
                        $this->pdo->query($sql, $values);
                    } catch (\Exception $e) {
                    }
                }
                $count += 1;
            }
            fclose($file);
        }
    }
}

$cli = new Annotations();
$cli->run();
