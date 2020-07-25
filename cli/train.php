<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rubix\ML\CrossValidation\Metrics\RSquared;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\NeuralNet\CostFunctions\LeastSquares;
use Rubix\ML\NeuralNet\Layers\Dense;
use Rubix\ML\NeuralNet\Layers\Activation;
use Rubix\ML\NeuralNet\ActivationFunctions\ReLU;
use Rubix\ML\NeuralNet\Optimizers\RMSProp;
use Rubix\ML\Other\Loggers\Screen;
use Rubix\ML\Regressors\MLPRegressor;

$estimator = new MLPRegressor([
    new Dense(100),
    new Activation(new ReLU()),
    new Dense(100),
    new Activation(new ReLU()),
    new Dense(50),
    new Activation(new ReLU()),
    new Dense(50),
    new Activation(new ReLU()),
], 128, new RMSProp(0.001), 1e-3, 100, 1e-5, 3, 0.1, new LeastSquares(), new RSquared());

var_dump($estimator->trained());

$samples = [
    [0.1, 20, 1],
    [2.0, -5, 2],
    [0.01, 5, 1],
    [0.1, 20, 1],
    [2.0, -5, 2],
    [0.01, 5, 1],
    [0.1, 20, 1],
    [2.0, -5, 2],
    [0.01, 5, 1],
    [0.01, 5, 1],
];

$labels = [0.5, 0.6, 0.7, 0.5, 0.6, 0.7, 0.5, 0.6, 0.7, 0.2];

$dataset = new Labeled($samples, $labels);

$estimator->setLogger(new Screen('example'));
$estimator->partial($dataset);

$samples = [
    [4, 3, 44.2],
    [2, 2, 16.7],
    [2, 4, 19.5],
    [3, 3, 55.0],
];

$dataset = new Unlabeled($samples);

$predictions = $estimator->predict($dataset);

var_dump($predictions);
