<?php

namespace ChessData\Cli\DataPrepare\Training;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use Chess\Combinatorics\RestrictedPermutationWithRepetition;
use Chess\Heuristic\HeuristicPicture;
use Chess\ML\Supervised\Regression\OptimalLinearCombinationLabeller;
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

        $options->setHelp('Creates a prepared CSV dataset of heuristics in the dataset/training folder.');
        $options->registerArgument('n', 'A random number of games to be queried.', true);
        $options->registerArgument('player', "The chess player's full name.", true);
        $options->registerOption('win', 'The player wins.');
        $options->registerOption('lose', 'The player loses.');
        $options->registerOption('draw', 'Draw.');
    }

    protected function main(Options $options)
    {
        if ($options->getOpt('win')) {
            $result = '0-1';
        } elseif ($options->getOpt('lose')) {
            $result = '1-0';
        } else {
            $result = '1/2-1/2';
        }

        $opt = key($options->getOpt());
        $filename = "{$this->snakeCase($options->getArgs()[1])}_{$opt}.csv";

        $sql = "SELECT * FROM games WHERE Black SOUNDS LIKE '{$options->getArgs()[1]}'
            AND result = '$result'
            ORDER BY RAND()
            LIMIT {$options->getArgs()[0]}";

        $games = Pdo::getInstance()
                    ->query($sql)
                    ->fetchAll(\PDO::FETCH_ASSOC);

        $dimensions = (new HeuristicPicture(''))->getDimensions();

        $permutations = (new RestrictedPermutationWithRepetition())
            ->get(
                [ 8, 13, 21, 34],
                count($dimensions),
                100
            );

        $fp = fopen(self::DATA_FOLDER."/$filename", 'w');

        foreach ($games as $game) {
            try {
                $pic = (new HeuristicPicture($game['movetext']))->takeBalanced()->getPicture();
                foreach ($pic as $key => $val) {
                    $balance = (new OptimalLinearCombinationLabeller($permutations))->balance($val);
                    $row = array_merge($val, [$balance[Symbol::BLACK]]);
                    fputcsv($fp, $row, ';');
                }
            } catch (\Exception $e) {}
        }

        fclose($fp);
    }

    protected function snakeCase(string $string)
    {
        return str_replace(' ', '_', strtolower(trim($string)));
    }
}

$cli = new DataPrepareCli();
$cli->run();
