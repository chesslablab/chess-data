<?php

namespace ChessData\Cli\Prepare\Training\Classification;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Chess\Heuristics;
use Chess\Combinatorics\RestrictedPermutationWithRepetition;
use Chess\ML\Supervised\Classification\PermutationLabeller;
use Chess\PGN\Movetext;
use Chess\PGN\Symbol;
use ChessData\PdoCli;
use splitbrain\phpcli\Options;

/**
 * Prepares the data for further AI training.
 *
 * Plays chess games from the start position.
 */
class StartCli extends PdoCli
{
    const DATA_FOLDER = __DIR__.'/../../../../dataset/training/classification';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates a prepared CSV dataset in the dataset/training/classification folder.');
        $options->registerArgument('n', 'A random number of games to be queried.', true);
    }

    protected function main(Options $options)
    {
        $filename = "start_{$options->getArgs()[0]}_".time().'.csv';

        $sql = "SELECT * FROM games
            ORDER BY RAND()
            LIMIT {$options->getArgs()[0]}";

        $games = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        $dimensions = (new Heuristics(''))->getDimensions();

        $permutations = (new RestrictedPermutationWithRepetition())
            ->get(
                [ 4, 16 ],
                count($dimensions),
                100
            );

        $fp = fopen(self::DATA_FOLDER."/$filename", 'w');

        foreach ($games as $game) {
            try {
                $sequence = (new Movetext($game['movetext']))->sequence();
                foreach ($sequence as $movetext) {
                    $balance = (new Heuristics($movetext))->getBalance();
                    $end = end($balance);
                    $label = (new PermutationLabeller($permutations))->label($end);
                    $row = array_merge($end, [$label[Symbol::BLACK]]);
                    fputcsv($fp, $row, ';');
                }
            } catch (\Exception $e) {}
        }

        fclose($fp);
    }
}

$cli = new StartCli();
$cli->run();
