<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use PGNChess\Player;
use PGNChess\ML\Supervised\Regression\Labeller\Primes\Labeller as PrimesLabeller;
use PGNChess\ML\Supervised\Regression\Sampler\Primes\Sampler as PrimesSampler;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PGNChessData\Pdo;

const DATA_FOLDER = __DIR__.'/../../dataset';

$dotenv = Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load();

$sql = "SELECT * FROM games WHERE id BETWEEN {$argv[1]} AND {$argv[2]}";
$games = Pdo::getInstance()
            ->query($sql)
            ->fetchAll(\PDO::FETCH_ASSOC);

$filename = "{$argv[1]}_{$argv[2]}_beginner.csv";
$fp = fopen(DATA_FOLDER."/$filename", 'w');

foreach ($games as $game) {
    $player = new Player($game['movetext']);
    foreach ($player->getMoves() as $move) {
        $player->getBoard()->play(Convert::toStdObj(Symbol::WHITE, $move[0]));
        if (isset($move[1])) {
            $player->getBoard()->play(Convert::toStdObj(Symbol::BLACK, $move[1]));
        }
        $sample = (new PrimesSampler($player->getBoard()))->sample();
        $label = (new PrimesLabeller($sample))->label();
        $row = $sample[Symbol::WHITE];
        $row[] = $label[Symbol::BLACK];
        fputcsv($fp, $row, ';');
    }
}

fclose($fp);
