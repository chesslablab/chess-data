<?php

namespace ChessData\Cli\Csv;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\PdoCli;
use splitbrain\phpcli\Options;

class Players extends PdoCli
{
    const OUTPUT_FOLDER = __DIR__.'/../../output/';

    const OUTPUT_FILENAME = 'players.csv';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates the output/players.csv file.');
    }

    protected function main(Options $options)
    {
        $sql = "SELECT * FROM players";
        $players = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        $fp = fopen(self::OUTPUT_FOLDER.'/'.self::OUTPUT_FILENAME, 'w');
        $row = [
            'Event',
            'Site',
            'Date',
            'White',
            'Black',
            'Result',
            'ECO',
            'movetext',
        ];
        fputcsv($fp, $row, ',');
        foreach ($players as $player) {
            $row = [
              $player['Event'],
              $player['Site'],
              $player['Date'],
              $player['White'],
              $player['Black'],
              $player['Result'],
              $player['ECO'],
              $player['movetext'],
            ];
            fputcsv($fp, $row, ',');
        }
        fclose($fp);
    }
}

$cli = new Players();
$cli->run();
