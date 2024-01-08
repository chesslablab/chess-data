<?php

namespace ChessData\Cli\Json\Autocomplete;

require_once __DIR__ . '/../../../vendor/autoload.php';

use ChessData\PdoCli;
use splitbrain\phpcli\Options;

class Players extends PdoCli
{
    const OUTPUT_FOLDER = __DIR__.'/../../../output/';

    const OUTPUT_FILENAME = 'autocomplete-players.json';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates the output/autocomplete-players.json file.');
    }

    protected function main(Options $options)
    {
        $sql = "SELECT DISTINCT White AS name FROM games
          UNION
          SELECT DISTINCT Black FROM games AS name
          ORDER BY name";

        $arr = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_COLUMN);

        file_put_contents(self::OUTPUT_FOLDER.'/'.self::OUTPUT_FILENAME, json_encode($arr));
    }
}

$cli = new Players();
$cli->run();
