<?php

namespace ChessData\Cli\Seed;

require_once __DIR__ . '/../../vendor/autoload.php';

use Chess\Movetext\SanMovetext;
use Chess\Variant\Classical\PGN\Move;
use ChessData\Pdo;
use Dotenv\Dotenv;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Openings extends CLI
{
    protected Pdo $pdo;

    protected string $table = 'openings';

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
            $file = fopen($filepath, 'r');
            while (($line = fgetcsv($file)) !== false) {
                $move = new Move();
                $text = $line[2];
                if ($movetext = (new SanMovetext($move, $text))->validate()) {
                    $sql = "INSERT INTO {$this->table} (eco, name, movetext) VALUES (:eco, :name, :movetext)";
                    $values = [
                        [
                            'param' => ':eco',
                            'value' => $line[0],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':name',
                            'value' => $line[1],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':movetext',
                            'value' => $movetext,
                            'type' => \PDO::PARAM_STR,
                        ],
                    ];
                    try {
                        $this->pdo->query($sql, $values);
                    } catch (\Exception $e) {
                    }
                }
            }
            fclose($file);
        }
    }
}

$cli = new Openings();
$cli->run();
