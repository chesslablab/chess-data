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
echo "w e4" . PHP_EOL;

$sample = (new PrimesSampler($board))->sample();
$prediction = $estimator->predictSample($sample[Symbol::WHITE]);
$decoded = (new PrimesLabelDecoder($board))->decode(Symbol::BLACK, $prediction);

// b6
echo "b {$decoded}" . PHP_EOL;
$board->play(Convert::toStdObj(Symbol::BLACK, $decoded));
$board->play(Convert::toStdObj(Symbol::WHITE, 'd4'));
echo "w d4" . PHP_EOL;

$sample = (new PrimesSampler($board))->sample();
$prediction = $estimator->predictSample($sample[Symbol::WHITE]);
$decoded = (new PrimesLabelDecoder($board))->decode(Symbol::BLACK, $prediction);

// b5
echo "b {$decoded}" . PHP_EOL;
$board->play(Convert::toStdObj(Symbol::BLACK, $decoded));
$board->play(Convert::toStdObj(Symbol::WHITE, 'Nf3'));
echo "w Nf3" . PHP_EOL;

$sample = (new PrimesSampler($board))->sample();
$prediction = $estimator->predictSample($sample[Symbol::WHITE]);
$decoded = (new PrimesLabelDecoder($board))->decode(Symbol::BLACK, $prediction);

// Nc6
echo "b {$decoded}" . PHP_EOL;
$board->play(Convert::toStdObj(Symbol::BLACK, $decoded));
$board->play(Convert::toStdObj(Symbol::WHITE, 'Bxb5'));
echo "w Bxb5" . PHP_EOL;

$sample = (new PrimesSampler($board))->sample();
$prediction = $estimator->predictSample($sample[Symbol::WHITE]);
$decoded = (new PrimesLabelDecoder($board))->decode(Symbol::BLACK, $prediction);

// Nh6
echo "b {$decoded}" . PHP_EOL;
$board->play(Convert::toStdObj(Symbol::BLACK, $decoded));
$board->play(Convert::toStdObj(Symbol::WHITE, 'Bxc6'));
echo "w Bxc6" . PHP_EOL;

echo $board->getMovetext() . PHP_EOL;
