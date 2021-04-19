<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use Chess\Event\Picture\Standard as StandardEventPicture;
use Chess\Heuristic\Picture\Standard as StandardHeuristicPicture;
use Chess\ML\Supervised\Regression\Labeller\Primes\Labeller as PrimesLabeller;
use Chess\PGN\Symbol;
use ChessData\Pdo;

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
    try {
        $eventPicture = (new StandardEventPicture($game['movetext']))->take();
        $heuristicPicture = (new StandardHeuristicPicture($game['movetext']))->take();
        $picture = [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ];
        for ($i = 0; $i < count($heuristicPicture[Symbol::WHITE]); $i++) {
            $picture[Symbol::WHITE][$i] = array_merge(
                $eventPicture[Symbol::WHITE][$i],
                $heuristicPicture[Symbol::WHITE][$i]
            );
            $picture[Symbol::BLACK][$i] = array_merge(
                $eventPicture[Symbol::BLACK][$i],
                $heuristicPicture[Symbol::BLACK][$i]
            );
            $label = (new PrimesLabeller([
                Symbol::WHITE => $picture[Symbol::WHITE][$i],
                Symbol::BLACK => $picture[Symbol::BLACK][$i]
            ]))->label();
            $row = array_merge(
                $picture[Symbol::WHITE][$i],
                [$label[Symbol::BLACK]]
            );
            fputcsv($fp, $row, ';');
        }
    } catch (\Exception $e) {
        // do nothing
    }
}

fclose($fp);
