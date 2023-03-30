<?php

namespace ChessData\Cli\Model\Train;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Rubix\ML\PersistentModel;
use Rubix\ML\Classifiers\MultilayerPerceptron;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Extractors\CSV;
use Rubix\ML\Loggers\Screen;
use Rubix\ML\NeuralNet\Layers\Dense;
use Rubix\ML\NeuralNet\Layers\Dropout;
use Rubix\ML\NeuralNet\Layers\Activation;
use Rubix\ML\NeuralNet\Layers\PReLU;
use Rubix\ML\NeuralNet\ActivationFunctions\LeakyReLU;
use Rubix\ML\NeuralNet\Optimizers\Adam;
use Rubix\ML\NeuralNet\CostFunctions\CrossEntropy;
use Rubix\ML\CrossValidation\Metrics\MCC;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Serializers\RBX;
use Rubix\ML\Transformers\NumericStringConverter;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class ClassificationCli extends CLI
{
    const DATASET_FOLDER = __DIR__.'/../../../dataset/training/classification';
    const ML_FOLDER = __DIR__.'/../../../ml/classification';

    protected function setup(Options $options)
    {
        $options->setHelp('Trains a machine learning classifier.');
        $options->registerArgument('name', 'The name of the machine learning classifier.', true);
        $options->registerArgument('dataset', 'A prepared dataset in CSV format.', true);
    }

    protected function main(Options $options)
    {
        $extractor = new CSV(self::DATASET_FOLDER."/{$options->getArgs()[1]}", false, ';');

        $dataset = Labeled::fromIterator($extractor)
            ->apply(new NumericStringConverter());

        $filepath = self::ML_FOLDER."/{$options->getArgs()[0]}.rbx";

        if (file_exists(self::ML_FOLDER."/{$options->getArgs()[0]}.rbx")) {
            $estimator = PersistentModel::load(new Filesystem($filepath), new RBX());
        } else {
            $mlpClassifier = new MultilayerPerceptron([
                new Dense(200),
                new Activation(new LeakyReLU()),
                new Dropout(0.3),
                new Dense(100),
                new Activation(new LeakyReLU()),
                new Dropout(0.3),
                new Dense(50),
                new PReLU(),
            ], 128, new Adam(0.001), 1e-4, 1000, 1e-3, 3, 0.1, new CrossEntropy(), new MCC());

            $estimator = new PersistentModel($mlpClassifier, new Filesystem($filepath), new RBX());
        }

        $estimator->setLogger(new Screen($filepath));
        $estimator->train($dataset);
        $estimator->save();
    }
}

$cli = new ClassificationCli();
$cli->run();
