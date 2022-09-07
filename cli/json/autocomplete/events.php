<?php

namespace ChessData\Cli\Json\Autocomplete;

require_once __DIR__ . '/../../../vendor/autoload.php';

use ChessData\PdoCli;
use splitbrain\phpcli\Options;

class Events extends PdoCli
{
    const OUTPUT_FOLDER = __DIR__.'/../../../output/';

    const OUTPUT_FILENAME = 'autocomplete-events.json';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates the output/autocomplete-events.json file.');
    }

    protected function main(Options $options)
    {
        $sql = "SELECT DISTINCT Event FROM players ORDER BY Event";

        $arr = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        file_put_contents(self::OUTPUT_FOLDER.'/'.self::OUTPUT_FILENAME, json_encode($arr));
    }
}

$cli = new Events();
$cli->run();
