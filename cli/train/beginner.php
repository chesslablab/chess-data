<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use PGNChess\Player;
use PGNChess\ML\Supervised\Regression\Labeller\Primes\Labeller as PrimesLabeller;
use PGNChess\ML\Supervised\Regression\Sampler\Primes\Sampler as PrimesSampler;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PGNChessData\Pdo;
use Rubix\ML\PersistentModel;
use Rubix\ML\CrossValidation\Metrics\RSquared;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\NeuralNet\CostFunctions\LeastSquares;
use Rubix\ML\NeuralNet\Layers\Dense;
use Rubix\ML\NeuralNet\Layers\Activation;
use Rubix\ML\NeuralNet\ActivationFunctions\ReLU;
use Rubix\ML\NeuralNet\Optimizers\RMSProp;
use Rubix\ML\Other\Loggers\Screen;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Regressors\MLPRegressor;

const DATA_FOLDER = __DIR__.'/../../model';

$dotenv = Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load();

$sql = 'SELECT * FROM games LIMIT 10';

$games = Pdo::getInstance()
            ->query($sql)
            ->fetchAll(\PDO::FETCH_ASSOC);

$samples = [];
$labels = [];

foreach ($games as $game) {
    $player = new Player($game['movetext']);
    foreach ($player->getMoves() as $move) {
        $player->getBoard()->play(Convert::toStdObj(Symbol::WHITE, $move[0]));
        if (isset($move[1])) {
            $player->getBoard()->play(Convert::toStdObj(Symbol::BLACK, $move[1]));
        }
        $sample = (new PrimesSampler($player->getBoard()))->sample();
        $label = (new PrimesLabeller($sample))->label();
        $samples[] = $sample[Symbol::WHITE];
        $labels[] = $label[Symbol::BLACK];
    }
}

$dataset = new Labeled($samples, $labels);

$mlpRegressor = new MLPRegressor([
    new Dense(100),
    new Activation(new ReLU()),
    new Dense(100),
    new Activation(new ReLU()),
    new Dense(50),
    new Activation(new ReLU()),
    new Dense(50),
    new Activation(new ReLU()),
], 128, new RMSProp(0.001), 1e-3, 100, 1e-5, 3, 0.1, new LeastSquares(), new RSquared());

$estimator = new PersistentModel($mlpRegressor, new Filesystem(DATA_FOLDER.'/beginner.model'));

$estimator->setLogger(new Screen('beginner'));
$estimator->train($dataset);
$estimator->save();
