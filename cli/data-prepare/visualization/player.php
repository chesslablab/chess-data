<?php

namespace ChessData\Cli\DataPrepare\Training;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use Chess\PGN\Symbol;
use ChessData\Pdo;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Player extends CLI
{
    const DATA_FOLDER = __DIR__.'/../../../dataset/visualization';

    protected $conf;

    protected function setup(Options $options)
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../../../');
        $dotenv->load();

        $options->setHelp('Creates a prepared JSON dataset of heuristics in the dataset/visualization folder.');
        $options->registerArgument('n', 'A random number of games to be queried.', true);
        $options->registerArgument('player', "The chess player's full name.", true);
        $options->registerOption('win', 'The player wins.');
        $options->registerOption('lose', 'The player loses.');
        $options->registerOption('draw', 'Draw.');

        $this->conf = include(__DIR__.'/../../../config/database.php');
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
        $filename = "{$this->snakeCase($options->getArgs()[1])}_{$opt}.json";

        $sql = "SELECT * FROM games WHERE Black SOUNDS LIKE '{$options->getArgs()[1]}'
            AND result = '$result'
            ORDER BY RAND()
            LIMIT {$options->getArgs()[0]}";

        $games = Pdo::getInstance($this->conf)->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        $fp = fopen(self::DATA_FOLDER."/$filename", 'w');
        fwrite($fp, json_encode($games));
        fclose($fp);
    }

    protected function snakeCase(string $string)
    {
        return str_replace(' ', '_', strtolower(trim($string)));
    }
}

$cli = new Player();
$cli->run();
