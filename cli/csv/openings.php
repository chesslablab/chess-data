<?php

namespace ChessData\Cli\Csv;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\PdoCli;
use splitbrain\phpcli\Options;

class Openings extends PdoCli
{
    const OUTPUT_FOLDER = __DIR__.'/../../output/';

    const OUTPUT_FILENAME = 'openings.csv';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates the output/openings.csv file.');
    }

    protected function main(Options $options)
    {
        $sql = "SELECT * FROM openings";
        $openings = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        $fp = fopen(self::OUTPUT_FOLDER.'/'.self::OUTPUT_FILENAME, 'w');
        foreach ($openings as $opening) {
            unset($opening['id']);
            fputcsv($fp, $opening, ',');
        }
        fclose($fp);
    }
}

$cli = new Openings();
$cli->run();
