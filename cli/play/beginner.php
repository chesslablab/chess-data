<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PGNChess\Game;
use PGNChess\Heuristic\AttackSnapshot;
use PGNChess\Heuristic\CenterSnapshot;
use PGNChess\Heuristic\CheckSnapshot;
use PGNChess\Heuristic\ConnectivitySnapshot;
use PGNChess\Heuristic\KingSafetySnapshot;
use PGNChess\Heuristic\MaterialSnapshot;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;

const DATA_FOLDER = __DIR__.'/../../model';

$estimator = PersistentModel::load(new Filesystem(DATA_FOLDER.'/beginner.model'));
$g = new Game();
$snapshots = [];

$g->play('w', 'e4');

$snapshots[] = [
     (new AttackSnapshot($g->movetext()))->take()[0],
     (new ConnectivitySnapshot($g->movetext()))->take()[0],
     (new CenterSnapshot($g->movetext()))->take()[0],
     (new KingSafetySnapshot($g->movetext()))->take()[0],
     (new MaterialSnapshot($g->movetext()))->take()[0],
     (new CheckSnapshot($g->movetext()))->take()[0],
];

$last = end($snapshots);

$sample = [
    $last[0]['w'],
    $last[1]['w'],
    $last[2]['w'],
    $last[3]['w'],
    $last[4]['w'],
    $last[5]['w'],
];

$prediction = $estimator->predictSample($sample);

var_dump($prediction);
