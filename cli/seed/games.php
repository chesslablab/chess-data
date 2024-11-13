<?php

namespace ChessData\Cli\Seed;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\Pdo;
use ChessData\Pgn\Seeder;
use Dotenv\Dotenv;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Games extends CLI
{
    protected Pdo $pdo;

    protected string $table = 'games';

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
        $options->registerArgument('filepath', 'PGN file or folder with PGN files.', true);
    }

    protected function main(Options $options)
    {
        if (is_file($options->getArgs()[0])) {
            $result = $this->seed($options->getArgs()[0]);
            $this->display($result);
        } elseif (is_dir($options->getArgs()[0])) {
            $dir = __DIR__ . '/../../' . $options->getArgs()[0];
            $dirIterator = new \DirectoryIterator($dir);
            foreach ($dirIterator as $fileinfo) {
                if (!$fileinfo->isDot()) {
                    $result = $this->seed("$dir/{$fileinfo->getFilename()}");
                    $this->display($result);
                }
            }
        }
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

    protected function seed(string $filepath): \stdClass
    {
        $seeder = new Seeder($this->pdo, $this->table, $filepath);

        try {
            $seeder->seed();
        } catch (\Exception $e) {
        }

        return $seeder->getResult();
    }
}

$cli = new Games();
$cli->run();
