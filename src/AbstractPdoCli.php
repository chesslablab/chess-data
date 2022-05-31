<?php

namespace ChessData;

use ChessData\Seeder\Seeder;
use Dotenv\Dotenv;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

abstract class AbstractPdoCli extends CLI
{
    protected $pdo;

    protected $table;

    public function __construct()
    {
        parent::__construct(true);

        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();

        $conf = include(__DIR__.'/../config/database.php');

        $this->pdo = Pdo::getInstance($conf);
    }

    protected function setup(Options $options)
    {
        $options->setHelp("Seeds the {$this->table} table.");
        $options->registerArgument('filepath', 'PGN file or folder with PGN files.', true);
    }

    protected function display(\stdClass $result)
    {
        if ($result->valid === 0) {
            $this->error('Whoops! It seems as if no valid games were found in this file.');
        } else {
            $invalid = $result->total - $result->valid;
            if ($invalid > 0) {
                $this->error("{$invalid} games did not pass the validation.");
            }
            $this->success("{$result->valid} games out of a total of {$result->total} are OK.");
        }
    }

    protected function seed(string $filepath)
    {
        $result = new \stdClass();

        try {
            $result = (new Seeder($this->pdo, $this->table, $filepath))->seed();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return $result;
    }
}
