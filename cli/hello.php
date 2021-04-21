<?php

require __DIR__ . '/../vendor/autoload.php';

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Hello extends CLI
{
    protected function setup(Options $options)
    {
        $options->setHelp('Hello world!');
        $options->registerOption('version', 'print version', 'v');
    }

    protected function main(Options $options)
    {
        if ($options->getOpt('version')) {
            $this->info('1.0.0');
        } else {
            echo $options->help();
        }
    }
}

$cli = new Hello();
$cli->run();
