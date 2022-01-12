<?php

namespace ChessData\Cli\Seed;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\PdoCli;
use ChessData\Seeder\Basic as BasicSeeder;
use ChessData\Seeder\Heuristic as HeuristicSeeder;
use splitbrain\phpcli\Options;

class Games extends PdoCli
{
    protected function setup(Options $options)
    {
        $options->setHelp('Seeds the games table with the specified PGN games.');
        $options->registerOption('heuristics', 'Add heuristics for further data visualization.');
        $options->registerArgument('filepath', 'PGN file, or folder containing the PGN files.', true);
    }

    protected function main(Options $options)
    {
        if (is_file($options->getArgs()[0])) {
            $result = $this->seed($options->getArgs()[0], $options->getOpt('heuristics'));
            $this->display($result);
        } elseif (is_dir($options->getArgs()[0])) {
            $dir = __DIR__.'/../../'.$options->getArgs()[0];
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
                ? $result = (new HeuristicSeeder($this->pdo, $filepath))->seed()
                : $result = (new BasicSeeder($this->pdo, $filepath))->seed();
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

$cli = new Games();
$cli->run();
