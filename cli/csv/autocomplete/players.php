<?php

namespace ChessData\Cli\Csv;

require_once __DIR__ . '/../../../vendor/autoload.php';

use ChessData\PdoCli;
use splitbrain\phpcli\Options;

class Players extends PdoCli
{
    const OUTPUT_FOLDER = __DIR__.'/../../../output/';

    const OUTPUT_FILENAME = 'autocomplete-players.csv';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates the output/autocomplete-players.csv file.');
    }

    /**
     * Run sql/cleanup-autocomplete-players.sql after the command is run.
     */
    protected function main(Options $options)
    {
        $sql = "SELECT DISTINCT White AS name FROM players
          UNION
          SELECT DISTINCT Black FROM players AS name
          ORDER BY name";
        $players = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        $fp = fopen(self::OUTPUT_FOLDER.'/'.self::OUTPUT_FILENAME, 'w');
        $row = [
            'name',
        ];
        fputcsv($fp, $row, ',');
        foreach ($players as $player) {
            $row = [
              $player['name'],
            ];
            fputcsv($fp, $row, ',');
        }
        fclose($fp);
    }
}

$cli = new Players();
$cli->run();
