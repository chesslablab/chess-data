<?php

namespace ChessData;

use Chess\Movetext;
use Chess\Variant\Classical\PGN\Move;
use splitbrain\phpcli\Options;

abstract class PdoOpeningCli extends AbstractPdoCli
{
    protected function main(Options $options)
    {
        foreach (scandir($this->inputFolder) as $item) {
            $this->seed($this->inputFolder . "/$item");
        }
    }

    protected function seed(string $filepath)
    {
        if (is_file($filepath)) {
            $file = fopen($filepath, 'r');
            while (($line = fgetcsv($file)) !== FALSE) {
                $move = new Move();
                $text = $line[2];
                if ($movetext = (new Movetext($move, $text))->validate()) {
                    $sql = "INSERT INTO {$this->table} (eco, name, movetext) VALUES (:eco, :name, :movetext)";
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
