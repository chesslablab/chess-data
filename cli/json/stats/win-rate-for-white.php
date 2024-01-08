<?php

namespace ChessData\Cli\Json\Stats;

require_once __DIR__ . '/../../../vendor/autoload.php';

use ChessData\PdoCli;
use splitbrain\phpcli\Options;

class WinRateForWhite extends PdoCli
{
    const OUTPUT_FOLDER = __DIR__.'/../../../output/';

    const OUTPUT_FILENAME = 'win-rate-for-white.json';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates the output/win-rate-for-white.json file.');
    }

    protected function main(Options $options)
    {
        $sql = "SELECT ECO, COUNT(*) AS total
            FROM games
            WHERE Result = '1-0'
            GROUP BY ECO
            HAVING total >= 100
            ORDER BY total DESC
            LIMIT 50";

        $arr = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        file_put_contents(self::OUTPUT_FOLDER.'/'.self::OUTPUT_FILENAME, json_encode($arr));
    }
}

$cli = new WinRateForWhite();
$cli->run();
