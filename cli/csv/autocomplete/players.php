<?php

namespace ChessData\Cli\Csv;

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
        $sql = "SELECT DISTINCT White AS name FROM players
          UNION
          SELECT DISTINCT Black FROM players AS name
          ORDER BY name";

        $players = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        $json = json_encode($players);

        file_put_contents(self::OUTPUT_FOLDER.'/'.self::OUTPUT_FILENAME, $json);
    }
}

$cli = new Players();
$cli->run();
