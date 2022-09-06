<?php

namespace ChessData\Cli\Csv;

require_once __DIR__ . '/../../../vendor/autoload.php';

use ChessData\PdoCli;
use splitbrain\phpcli\Options;

class WinRateForBlack extends PdoCli
{
    const OUTPUT_FOLDER = __DIR__.'/../../../output/';

    const OUTPUT_FILENAME = 'win-rate-for-black.csv';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates the output/win-rate-for-black.csv file.');
    }

    protected function main(Options $options)
    {
        $sql = "SELECT ECO, COUNT(*) AS total
            FROM players
            WHERE Result = '0-1'
            GROUP BY ECO
            HAVING total >= 100
            ORDER BY total DESC
            LIMIT 50";
        $players = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        $fp = fopen(self::OUTPUT_FOLDER.'/'.self::OUTPUT_FILENAME, 'w');
        $row = [
            'ECO',
            'total',
        ];
        fputcsv($fp, $row, ',');
        foreach ($players as $player) {
            $row = [
              $player['ECO'],
              $player['total'],
            ];
            fputcsv($fp, $row, ',');
        }
        fclose($fp);
    }
}

$cli = new WinRateForBlack();
$cli->run();