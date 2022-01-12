<?php

namespace ChessData\Cli\Seed;

require_once __DIR__ . '/../../vendor/autoload.php';

use Chess\PGN\Validate;
use ChessData\PdoCli;
use splitbrain\phpcli\Options;

class Openings extends PdoCli
{
    const DATA_FOLDER = __DIR__.'/../../data/chess-openings';

    protected function setup(Options $options)
    {
        $options->setHelp('Seeds the openings table.');
    }

    protected function main(Options $options)
    {
        foreach (scandir(self::DATA_FOLDER) as $item) {
            $this->seed(self::DATA_FOLDER . "/$item");
        }
    }

    protected function seed(string $filepath)
    {
        if (is_file($filepath)) {
            $file = fopen($filepath, 'r');
            while (($line = fgetcsv($file)) !== FALSE) {
                if ($movetext = Validate::movetext($line[2])) {
                    $sql = 'INSERT INTO openings (eco, name, movetext) VALUES (:eco, :name, :movetext)';
                    $values = [
                        [
                            'param' => ':eco',
                            'value' => $line[0],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':name',
                            'value' => $line[1],
                            'type' => \PDO::PARAM_STR,
                        ],
                        [
                            'param' => ':movetext',
                            'value' => $movetext,
                            'type' => \PDO::PARAM_STR,
                        ],
                    ];

                    try {
                        $this->pdo->query($sql, $values);
                    } catch (\Exception $e) {}
                }
            }
            fclose($file);
        }
    }
}

$cli = new Openings();
$cli->run();
