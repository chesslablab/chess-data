<?php

namespace ChessData\Cli;

require_once __DIR__ . '/../vendor/autoload.php';

use Chess\Game;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class ModelPlayCli extends CLI
{
    protected function setup(Options $options)
    {
        $options->setHelp('Play with the AI.');
    }

    protected function main(Options $options)
    {
        $game = new Game(Game::MODE_PVA);
        $game->play('w', 'd4');
        $game->play('b', $game->response());
        $game->play('w', 'Nc3');
        $game->play('b', $game->response());
        $game->play('w', 'Nxd5');
        $game->play('b', $game->response());


        // TODO
        echo $game->movetext() . PHP_EOL;
    }
}

$cli = new ModelPlayCli();
$cli->run();
