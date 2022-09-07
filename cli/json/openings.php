<?php

namespace ChessData\Cli\Json;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\PdoCli;
use splitbrain\phpcli\Options;

class Openings extends PdoCli
{
    const OUTPUT_FOLDER = __DIR__.'/../../output/';

    const OUTPUT_FILENAME = 'openings.json';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates the output/openings.json file.');
    }

    protected function main(Options $options)
    {
        $sql = "SELECT eco, name, movetext FROM openings";

        $arr = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        file_put_contents(self::OUTPUT_FOLDER.'/'.self::OUTPUT_FILENAME, json_encode($arr));
    }
}

$cli = new Openings();
$cli->run();
