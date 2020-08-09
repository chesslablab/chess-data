<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Rubix\ML\PersistentModel;
use Rubix\ML\CrossValidation\Metrics\RSquared;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Extractors\CSV;
use Rubix\ML\Extractors\ColumnPicker;
use Rubix\ML\NeuralNet\CostFunctions\LeastSquares;
use Rubix\ML\NeuralNet\Layers\Dense;
use Rubix\ML\NeuralNet\Layers\Activation;
use Rubix\ML\NeuralNet\ActivationFunctions\ReLU;
use Rubix\ML\NeuralNet\Optimizers\RMSProp;
use Rubix\ML\Other\Loggers\Screen;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Regressors\MLPRegressor;
use Rubix\ML\Transformers\NumericStringConverter;

const DATASET_FOLDER = __DIR__.'/../../dataset';
const MODEL_FOLDER = __DIR__.'/../../model';

$extractor = new ColumnPicker(new CSV(DATASET_FOLDER."/{$argv[1]}", false, ';'), [0, 1, 2, 3, 4, 5, 6]);

$dataset = Labeled::fromIterator($extractor)
    ->apply(new NumericStringConverter())
    ->transformLabels('floatval');

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

$estimator = new PersistentModel($mlpRegressor, new Filesystem(MODEL_FOLDER.'/beginner.model'));

$estimator->setLogger(new Screen('beginner'));
$estimator->train($dataset);
$estimator->save();
