<?php

namespace ChessData\Cli\DataPrepare\Training;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use Chess\PGN\Symbol;
use ChessData\Pdo;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class DataPrepareCli extends CLI
{
    const DATA_FOLDER = __DIR__.'/../../../dataset/visualization';

    protected function setup(Options $options)
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../../../');
        $dotenv->load();

        $options->setHelp('Creates a prepared dataset of heuristics in JSON format for further visualization.');
        $options->registerArgument('from', 'The id range.', true);
        $options->registerArgument('to', 'The id range.', true);
    }

    protected function main(Options $options)
    {
        $sql = "SELECT * FROM games WHERE id BETWEEN {$options->getArgs()[0]} AND {$options->getArgs()[1]}";

        $games = Pdo::getInstance()
                    ->query($sql)
                    ->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];
        foreach ($games as $game) {
            $result[] = $game;
        }

        $filename = "{$options->getArgs()[0]}_{$options->getArgs()[1]}.json";
        $fp = fopen(self::DATA_FOLDER."/$filename", 'w');
        fwrite($fp, json_encode($result));
        fclose($fp);
    }
}

$cli = new DataPrepareCli();
$cli->run();
