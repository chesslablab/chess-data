<?php

namespace ChessData\Cli\DataPrepare\Training;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use Chess\Heuristic\Picture\Weighted as WeightedHeuristicPicture;
use Chess\ML\Supervised\Regression\Labeller\PrimesLabeller;
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
                $heuristicPicture = (new WeightedHeuristicPicture($game['movetext']))->take();
                $picture = [
                    Symbol::WHITE => [],
                    Symbol::BLACK => [],
                ];
                for ($i = 0; $i < count($heuristicPicture[Symbol::WHITE]); $i++) {
                    $picture[Symbol::WHITE][$i] = $heuristicPicture[Symbol::WHITE][$i];
                    $picture[Symbol::BLACK][$i] = $heuristicPicture[Symbol::BLACK][$i];

                    $label = (new PrimesLabeller([
                        Symbol::WHITE => $picture[Symbol::WHITE][$i],
                        Symbol::BLACK => $picture[Symbol::BLACK][$i]
                    ]))->label();

                    $row = array_merge(
                        $picture[Symbol::BLACK][$i],
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
