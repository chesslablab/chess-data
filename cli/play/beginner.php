<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PGNChess\Game;

$game = new Game(Game::MODE_PVA);
$game->play('w', 'e4');
$game->play('b', $game->response());
$game->play('w', 'd4');
$game->play('b', $game->response());
$game->play('w', 'e5');
$game->play('b', $game->response());
$game->play('w', 'e6');
$game->play('b', $game->response());



// TODO
echo $game->movetext() . PHP_EOL;
