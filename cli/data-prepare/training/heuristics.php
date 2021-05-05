<?php

namespace ChessData\Cli\DataPrepare\Training;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use Chess\Heuristic\Picture\Standard as StandardHeuristicPicture;
use Chess\ML\Supervised\Regression\Labeller\LinearCombinationLabeller;
use Chess\PGN\Symbol;
use ChessData\Pdo;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class DataPrepareCli extends CLI
{
    const DATA_FOLDER = __DIR__.'/../../../dataset/training';

    protected function setup(Options $options)
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../../../');
        $dotenv->load();

        $options->setHelp('Creates a prepared dataset of heuristics in CSV format for further training.');
        $options->registerArgument('name', 'The model name to be trained.', true);
        $options->registerArgument('from', 'The id range.', true);
        $options->registerArgument('to', 'The id range.', true);
    }

    protected function main(Options $options)
    {
        $sql = "SELECT * FROM games WHERE id BETWEEN {$options->getArgs()[1]} AND {$options->getArgs()[2]}";

        $games = Pdo::getInstance()
                    ->query($sql)
                    ->fetchAll(\PDO::FETCH_ASSOC);

        $filename = "{$options->getArgs()[0]}_{$options->getArgs()[1]}_{$options->getArgs()[2]}.csv";

        $fp = fopen(self::DATA_FOLDER."/$filename", 'w');

        foreach ($games as $game) {
            try {
                $heuristicPicture = new StandardHeuristicPicture($game['movetext']);
                $taken = $heuristicPicture->take();
                foreach ($taken[Symbol::WHITE] as $key => $item) {
                    $sample = [
                        Symbol::WHITE => $taken[Symbol::WHITE][$key],
                        Symbol::BLACK => $taken[Symbol::BLACK][$key],
                    ];
                    $label = (new LinearCombinationLabeller($heuristicPicture, $sample))->label();
                    $row = array_merge(
                        $taken[Symbol::BLACK][$key],
                        [$label[Symbol::BLACK]]
                    );
                    fputcsv($fp, $row, ';');
                }
            } catch (\Exception $e) {}
        }

        fclose($fp);
    }
}

$cli = new DataPrepareCli();
$cli->run();
