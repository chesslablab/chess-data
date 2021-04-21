<?php

namespace ChessData\Cli;

require_once __DIR__ . '/../vendor/autoload.php';

use ChessData\Exception\PgnFileCharacterEncodingException;
use ChessData\Validator\Syntax as SyntaxValidator;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Validate extends CLI
{
    protected function setup(Options $options)
    {
        $options->setHelp('PGN syntax validator.');
        $options->registerArgument('filepath', 'Large files (for example 50MB) may take a few seconds to be parsed.', true);
    }

    protected function main(Options $options)
    {
        try {
            $result = (new SyntaxValidator($options->getArgs()[0]))->syntax();
        } catch (PgnFileCharacterEncodingException $e) {
            $this->error($e->getMessage());
            exit;
        }

        if ($result->valid === 0) {
            $this->error('Whoops! It seems as if no valid games were found in this file.');
        } else {
            $invalid = $result->total - $result->valid;
            if ($invalid > 0) {
                $this->error("{$invalid} games did not pass the validation.");
            }
            $this->success("{$result->valid} games out of a total of {$result->total} are OK.");
        }
    }
}

$cli = new Validate();
$cli->run();
