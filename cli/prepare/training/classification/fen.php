<?php

namespace ChessData\Cli\Prepare\Training\Classification;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Chess\Combinatorics\RestrictedPermutationWithRepetition;
use Chess\FEN\StrToBoard;
use Chess\Heuristics\EvalFunction;
use Chess\Heuristics\SanHeuristics;
use Chess\ML\Supervised\Classification\PermutationLabeller;
use Chess\Movetext\SanMovetext;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;
use ChessData\PdoCli;
use splitbrain\phpcli\Options;

/**
 * Prepares the data for further AI training.
 *
 * Plays chess games starting from a FEN position.
 */
class FenCli extends PdoCli
{
    const DATA_FOLDER = __DIR__.'/../../../../dataset/training/classification';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates a prepared CSV dataset in the dataset/training/classification folder.');
        $options->registerArgument('n', 'A random number of games to be queried.', true);
    }

    protected function main(Options $options)
    {
        $filename = "fen_{$options->getArgs()[0]}_".time().'.csv';

        $sql = "SELECT * FROM endgames
            WHERE FEN IS NOT NULL
            ORDER BY RAND()
            LIMIT {$options->getArgs()[0]}";

        $games = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        $eval = (new EvalFunction())->getEval();

        $permutations = (new RestrictedPermutationWithRepetition())
            ->get(
                [ 4, 16 ],
                count($eval),
                100
            );

        $fp = fopen(self::DATA_FOLDER."/$filename", 'w');

        $move = new Move();

        foreach ($games as $game) {
            try {
                $sequence = (new SanMovetext($move, $game['movetext']))->sequence();
                $board = (new StrToBoard($game['FEN']))->create();
                foreach ($sequence as $movetext) {
                    $balance = (new SanHeuristics($movetext, $board))->getBalance();
                    $end = end($balance);
                    $label = (new PermutationLabeller($permutations))->label($end);
                    $row = array_merge($end, [$label[Color::B]]);
                    fputcsv($fp, $row, ';');
                }
            } catch (\Exception $e) {}
        }

        fclose($fp);
    }
}

$cli = new FenCli();
$cli->run();
