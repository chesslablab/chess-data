<?php

namespace ChessData\Cli\Model\Train;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Rubix\ML\PersistentModel;
use Rubix\ML\CrossValidation\Metrics\RSquared;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Extractors\CSV;
use Rubix\ML\NeuralNet\CostFunctions\LeastSquares;
use Rubix\ML\NeuralNet\Layers\Dense;
use Rubix\ML\NeuralNet\Layers\Activation;
use Rubix\ML\NeuralNet\ActivationFunctions\ReLU;
use Rubix\ML\NeuralNet\Optimizers\RMSProp;
use Rubix\ML\Loggers\Screen;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Regressors\MLPRegressor;
use Rubix\ML\Serializers\RBX;
use Rubix\ML\Transformers\NumericStringConverter;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class RegressionCli extends CLI
{
    const DATASET_FOLDER = __DIR__.'/../../../dataset/training/regression';
    const MODEL_FOLDER = __DIR__.'/../../../model/regression';

    protected function setup(Options $options)
    {
        $options->setHelp('Trains an ML regression model.');
        $options->registerArgument('name', 'The name of the regression model.', true);
        $options->registerArgument('dataset', 'A prepared dataset in CSV format.', true);
    }

    protected function main(Options $options)
    {
        $extractor = new CSV(self::DATASET_FOLDER."/{$options->getArgs()[1]}", false, ';');

        $dataset = Labeled::fromIterator($extractor)
            ->apply(new NumericStringConverter())
            ->transformLabels('floatval');

        $filepath = self::MODEL_FOLDER."/{$options->getArgs()[0]}.model";

        if (file_exists(self::MODEL_FOLDER."/{$options->getArgs()[0]}.model")) {
            $estimator = PersistentModel::load(new Filesystem($filepath), new RBX());
        } else {
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

            $estimator = new PersistentModel($mlpRegressor, new Filesystem($filepath), new RBX());
        }

        $estimator->setLogger(new Screen($filepath));
        $estimator->train($dataset);
        $estimator->save();
    }
}

$cli = new RegressionCli();
$cli->run();
