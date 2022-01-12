<?php

namespace ChessData\Cli\Seed;

require_once __DIR__ . '/../../vendor/autoload.php';

use ChessData\PdoCli;
use splitbrain\phpcli\Options;

class Openings extends PdoCli
{
    protected function setup(Options $options)
    {
        $options->setHelp('Seeds the openings table.');
    }

    protected function main(Options $options)
    {
        // TODO

        $result = $this->seed();
    }

    protected function seed()
    {
        $result = new \stdClass();

        // TODO

        return $result;
    }
}

$cli = new Openings();
$cli->run();
