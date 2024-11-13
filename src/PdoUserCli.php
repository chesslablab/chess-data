<?php

namespace ChessData;

use ChessData\Utils\Username;
use Dotenv\Dotenv;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

abstract class PdoUserCli extends CLI
{
    protected Pdo $pdo;

    protected string $table;

    protected Username $username;

    public function __construct()
    {
        parent::__construct(true);

        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();

        $conf = include(__DIR__.'/../config/database.php');

        $this->pdo = Pdo::getInstance($conf);
        $this->username = new Username();
    }

    protected function setup(Options $options)
    {
        $options->setHelp("Seeds the {$this->table} table.");
        $options->registerArgument('limit', 'Limit the number of users to be created.', true);
    }

    protected function main(Options $options)
    {
        $i = 0;
        $adjectives = $this->username->getAdjectives();
        $animals = $this->username->getAnimals();
        shuffle($adjectives);
        shuffle($animals);
        foreach ($animals as $animal) {
            foreach ($adjectives as $adjective) {
                if ($i < $options->getArgs()[0]) {
                    $this->seed($adjective . '_' . mb_strtolower($animal));
                    $i += 1;
                } else {
                    break 2;
                }
            }
        }
    }

    protected function seed(string $value)
    {
        $sql = "INSERT INTO {$this->table} (username) VALUES (:username)";
        $values = [
            [
                'param' => ':username',
                'value' => $value,
                'type' => \PDO::PARAM_STR,
            ],
        ];
        try {
            $this->pdo->query($sql, $values);
        } catch (\Exception $e) {}
    }
}
