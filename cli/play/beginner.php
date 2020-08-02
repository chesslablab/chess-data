<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PGNChess\Board;
use PGNChess\ML\Supervised\Regression\Labeller\Primes\Decoder as PrimesLabelDecoder;
use PGNChess\ML\Supervised\Regression\Sampler\Primes\Sampler as PrimesSampler;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;

const DATA_FOLDER = __DIR__.'/../../model';

$estimator = PersistentModel::load(new Filesystem(DATA_FOLDER.'/beginner.model'));

$board = new Board;
$board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));

$sample = (new PrimesSampler($board))->sample();

$prediction = $estimator->predictSample($sample[Symbol::WHITE]);
$decoded = (new PrimesLabelDecoder($board))->decode(Symbol::BLACK, $prediction);

echo "Prediction: {$prediction}" . PHP_EOL;
echo "Decoded: {$decoded}" . PHP_EOL;
