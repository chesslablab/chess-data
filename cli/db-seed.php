<?php

namespace ChessData\Cli;

require_once __DIR__ . '/../vendor/autoload.php';

use ChessData\Seeder\Basic as BasicSeeder;
use ChessData\Seeder\Heuristic as HeuristicSeeder;
use Dotenv\Dotenv;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class DbSeedCli extends CLI
{
    protected $conf;

    protected function setup(Options $options)
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();

        $options->setHelp('Seeds the chess database with the specified PGN games.');
        $options->registerOption('heuristics', 'Add heuristics for further data visualization.');
        $options->registerArgument('filepath', 'PGN file, or folder containing the PGN files.', true);

        $this->conf = include(__DIR__.'/../config/database.php');
    }

    protected function main(Options $options)
    {
        if (is_file($options->getArgs()[0])) {
            $result = $this->seed($options->getArgs()[0], $options->getOpt('heuristics'));
            $this->display($result);
        } elseif (is_dir($options->getArgs()[0])) {
            $dir = __DIR__.'/../'.$options->getArgs()[0];
            $dirIterator = new \DirectoryIterator($dir);
            foreach ($dirIterator as $fileinfo) {
                if (!$fileinfo->isDot()) {
                    $result = $this->seed("$dir/{$fileinfo->getFilename()}", $options->getOpt('heuristics'));
                    $this->display($result);
                }
            }
        }
    }

    protected function seed(string $filepath, bool $heuristics)
    {
        $result = new \stdClass();
        
        try {
            $heuristics
                ? $result = (new HeuristicSeeder($this->conf, $filepath))->seed()
                : $result = (new BasicSeeder($this->conf, $filepath))->seed();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return $result;
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
}

$cli = new DbSeedCli();
$cli->run();
