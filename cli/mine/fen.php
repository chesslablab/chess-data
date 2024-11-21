<?php

namespace ChessData\Cli\Mine;

require_once __DIR__ . '/../../vendor/autoload.php';

use Chess\Movetext\SanMovetext;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\Move;
use ChessData\Pdo;
use Dotenv\Dotenv;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Fen extends CLI
{
    protected Pdo $pdo;

    protected string $table = 'games';

    public function __construct()
    {
        parent::__construct();

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $conf = include(__DIR__ . '/../../config/database.php');

        $this->pdo = Pdo::getInstance($conf);
    }

    protected function setup(Options $options)
    {
        $options->setHelp('Extract chess positions in FEN format.');
        $options->registerArgument('player', 'The name of the player.', true);
    }

    protected function main(Options $options)
    {
        $values = [
            [
                'param' => ":player",
                'value' => $options->getArgs()[0],
                'type' => \PDO::PARAM_STR,
            ],
        ];

        $sql = "SELECT * FROM games WHERE White = :player OR Black = :player";

        $rows = $this->pdo->query($sql, $values)->fetchAll(\PDO::FETCH_ASSOC);

        $move = new Move();

        foreach ($rows as $row) {
            try {
                $value = '';
                $board = new Board();

                foreach ((new SanMovetext($move, $row['movetext']))->moves as $val) {
                    $board->play($board->turn, $val);
                    $value .= $board->history[count($board->history) - 1]['fen'] . ',';
                }

                $sql = "UPDATE {$this->table} SET fen_mine = :fen_mine WHERE movetext = :movetext";

                $values = [
                    [
                        'param' => ':fen_mine',
                        'value' => substr_replace($value, '', -1),
                        'type' => \PDO::PARAM_STR,
                    ],
                    [
                        'param' => ':movetext',
                        'value' => $row['movetext'],
                        'type' => \PDO::PARAM_STR,
                    ],
                ];

                $this->pdo->query($sql, $values);
            } catch (\Exception $e) {
            }
        }
    }
}

$cli = new Fen();
$cli->run();
