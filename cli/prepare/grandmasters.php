<?php

namespace ChessData\Cli\Prepare\Training;

require_once __DIR__ . '/../../vendor/autoload.php';

use Chess\PGN\Symbol;
use ChessData\PdoCli;
use splitbrain\phpcli\Options;

class Grandmasters extends PdoCli
{
    const DATA_FOLDER = __DIR__.'/../../model';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates the model/grandmasters.csv file with games by chess grandmasters.');
    }

    protected function main(Options $options)
    {
        $opt = key($options->getOpt());
        $filename = 'grandmasters.csv';

        $sql = "SELECT movetext FROM games WHERE result = '0-1'";

        $games = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        $fp = fopen(self::DATA_FOLDER."/$filename", 'w');

        foreach ($games as $game) {
            fputcsv($fp, $game, ';');
        }

        fclose($fp);
    }
}

$cli = new Grandmasters();
$cli->run();
