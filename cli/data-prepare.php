<?php

namespace ChessData\Cli;

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Chess\Event\Picture\Basic as BasicEventPicture;
use Chess\Heuristic\Picture\Weighted as WeightedHeuristicPicture;
use Chess\ML\Supervised\Regression\Labeller\PrimesLabeller;
use Chess\PGN\Symbol;
use ChessData\Pdo;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Command extends CLI
{
    const DATA_FOLDER = __DIR__.'/../dataset';

    protected function setup(Options $options)
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();

        $options->setHelp('Creates a prepared dataset in CSV format for further training.');
        $options->registerArgument('from', 'The id range.', true);
        $options->registerArgument('to', 'The id range.', true);    }

    protected function main(Options $options)
    {
        $sql = "SELECT * FROM games WHERE id BETWEEN {$options->getArgs()[0]} AND {$options->getArgs()[1]}";

        $games = Pdo::getInstance()
                    ->query($sql)
                    ->fetchAll(\PDO::FETCH_ASSOC);

        $filename = "{$options->getArgs()[0]}_{$options->getArgs()[1]}.csv";

        $fp = fopen(self::DATA_FOLDER."/$filename", 'w');

        foreach ($games as $game) {
            try {
                $eventPicture = (new BasicEventPicture($game['movetext']))->take();
                $heuristicPicture = (new WeightedHeuristicPicture($game['movetext']))->take();
                $picture = [
                    Symbol::WHITE => [],
                    Symbol::BLACK => [],
                ];
                for ($i = 0; $i < count($heuristicPicture[Symbol::WHITE]); $i++) {
                    $picture[Symbol::WHITE][$i] = array_merge(
                        $eventPicture[Symbol::WHITE][$i],
                        $heuristicPicture[Symbol::WHITE][$i]
                    );
                    $picture[Symbol::BLACK][$i] = array_merge(
                        $eventPicture[Symbol::BLACK][$i],
                        $heuristicPicture[Symbol::BLACK][$i]
                    );
                    $label = (new PrimesLabeller([
                        Symbol::WHITE => $picture[Symbol::WHITE][$i],
                        Symbol::BLACK => $picture[Symbol::BLACK][$i]
                    ]))->label();
                    $row = array_merge(
                        $picture[Symbol::WHITE][$i],
                        [$label[Symbol::BLACK]]
                    );
                    fputcsv($fp, $row, ';');
                }
            } catch (\Exception $e) {
                // do nothing
            }
        }

        fclose($fp);
    }
}

$cli = new Command();
$cli->run();
