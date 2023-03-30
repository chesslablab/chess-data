<?php

namespace ChessData\Cli\Prepare\Training\Regression;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Chess\Heuristics;
use Chess\PGN\Movetext;
use Chess\PGN\Symbol;
use ChessData\PdoCli;
use splitbrain\phpcli\Options;

/**
 * Prepares the data.
 *
 * Plays chess games starting from a FEN position.
 */
class FenCli extends PdoCli
{
    const DATA_FOLDER = __DIR__.'/../../../../dataset/training/regression';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates a prepared CSV dataset in the dataset/training/regression folder.');
        $options->registerArgument('n', 'A random number of games to be queried.', true);
    }

    protected function main(Options $options)
    {
        $filename = "start_{$options->getArgs()[0]}_".time().'.csv';

        $sql = "SELECT * FROM games
            ORDER BY RAND()
            LIMIT {$options->getArgs()[0]}";

        $games = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        $fp = fopen(self::DATA_FOLDER."/$filename", 'w');

        foreach ($games as $game) {
            try {
                $sequence = (new Movetext($game['movetext']))->sequence();
                foreach ($sequence as $movetext) {
                    $balance = (new Heuristics($movetext))->getBalance();
                    $end = end($balance);
                    $label = array_sum($end);
                    $row = array_merge($end, [$label]);
                    fputcsv($fp, $row, ';');
                }
            } catch (\Exception $e) {}
        }

        fclose($fp);
    }
}

$cli = new FenCli();
$cli->run();
