<?php

namespace ChessData\Cli\Csv;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\PdoCli;
use splitbrain\phpcli\Options;

class Tournaments extends PdoCli
{
    const OUTPUT_FOLDER = __DIR__.'/../../output/';

    const OUTPUT_FILENAME = 'tournaments.csv';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates the output/tournaments.csv file.');
    }

    protected function main(Options $options)
    {
        $sql = "SELECT * FROM tournaments";
        $tournaments = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
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
        foreach ($tournaments as $tournament) {
            $row = [
              $tournament['Event'],
              $tournament['Site'],
              $tournament['Date'],
              $tournament['White'],
              $tournament['Black'],
              $tournament['Result'],
              $tournament['ECO'],
              $tournament['movetext'],
            ];
            fputcsv($fp, $row, ',');
        }
        fclose($fp);
    }
}

$cli = new Tournaments();
$cli->run();
