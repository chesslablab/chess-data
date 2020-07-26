<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use PGNChess\Evaluation\Attack as AttackEvaluation;
use PGNChess\Evaluation\Center as CenterEvaluation;
use PGNChess\Evaluation\Check as CheckEvaluation;
use PGNChess\Evaluation\Connectivity as ConnectivityEvaluation;
use PGNChess\Evaluation\KingSafety as KingSafetyEvaluation;
use PGNChess\Evaluation\Material as MaterialEvaluation;
use PGNChess\ML\Supervised\Regression\Labeller\Primes as PrimesLabeller;
use PGNChess\PGN\Symbol;
use PGNChessData\Pdo;
use Rubix\ML\PersistentModel;
use Rubix\ML\CrossValidation\Metrics\RSquared;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
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

$sql = 'SELECT * FROM games LIMIT 1000';

$games = Pdo::getInstance()
            ->query($sql)
            ->fetchAll(\PDO::FETCH_ASSOC);

$samples = [];
$labels = [];

foreach ($games as $game) {
    $attack = json_decode($game['attack']);
    $connectivity = json_decode($game['connectivity']);
    $center = json_decode($game['center']);
    $kingSafety = json_decode($game['king_safety']);
    $material = json_decode($game['material']);
    $check = json_decode($game['check']);
    for ($i = 0; $i < count($attack); $i++) {
        $samples[] = [
            $attack[$i]->{Symbol::WHITE},
            $connectivity[$i]->{Symbol::WHITE},
            $center[$i]->{Symbol::WHITE},
            $kingSafety[$i]->{Symbol::WHITE},
            $material[$i]->{Symbol::WHITE},
            $check[$i]->{Symbol::WHITE},
        ];
        $labels[] = PrimesLabeller::WEIGHT[AttackEvaluation::NAME] * $attack[$i]->{Symbol::BLACK} +
                    PrimesLabeller::WEIGHT[ConnectivityEvaluation::NAME] * $connectivity[$i]->{Symbol::BLACK} +
                    PrimesLabeller::WEIGHT[CenterEvaluation::NAME] * $center[$i]->{Symbol::BLACK} +
                    PrimesLabeller::WEIGHT[KingSafetyEvaluation::NAME] * $kingSafety[$i]->{Symbol::BLACK} +
                    PrimesLabeller::WEIGHT[MaterialEvaluation::NAME] * $material[$i]->{Symbol::BLACK} +
                    PrimesLabeller::WEIGHT[CheckEvaluation::NAME] * $check[$i]->{Symbol::BLACK};
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
