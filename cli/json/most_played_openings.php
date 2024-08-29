<?php

namespace ChessData\Cli\Json;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\PdoCli;
use splitbrain\phpcli\Options;

class MostPlayedOpenings extends PdoCli
{
    protected function setup(Options $options)
    {
        $options->setHelp('Creates the output/openings.json file.');
    }

    protected function main(Options $options)
    {
        $sql = "SELECT ECO, COUNT(*) AS total
            FROM games
            WHERE Result = '1/2-1/2'
            GROUP BY ECO
            HAVING total >= 100
            ORDER BY total DESC
            LIMIT 50";

        $drawRate = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        $sql = "SELECT ECO, COUNT(*) AS total
            FROM games
            WHERE Result = '1-0'
            GROUP BY ECO
            HAVING total >= 100
            ORDER BY total DESC
            LIMIT 50";

        $winRateForWhite = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        $sql = "SELECT ECO, COUNT(*) AS total
            FROM games
            WHERE Result = '0-1'
            GROUP BY ECO
            HAVING total >= 100
            ORDER BY total DESC
            LIMIT 50";

        $winRateForBlack = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        $arr = [
            'drawRate' => $drawRate,
            'winRateForWhite' => $winRateForWhite,
            'winRateForBlack' => $winRateForBlack,
        ];

        echo json_encode($arr);
    }
}

$cli = new MostPlayedOpenings();
$cli->run();
