<?php

namespace ChessData;

use Chess\Movetext;
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
                if ($movetext = (new Movetext($line[2]))->validate()) {
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
