<?php

namespace ChessData;

use ChessData\Utils\Username;
use Dotenv\Dotenv;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

abstract class PdoUserCli extends CLI
{
    protected $pdo;

    protected $table;

    protected $username;

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
    }

    protected function main(Options $options)
    {
        foreach ($this->username->getAdjectives() as $adjective) {
            foreach ($this->username->getAnimals() as $animal) {
                $this->seed($adjective . $animal);
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
