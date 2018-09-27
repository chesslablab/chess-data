<?php

namespace PGNChess\Tests;

use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        if ($_ENV['APP_ENV'] !== 'test') {
            echo 'The tests can run on test environment only.' . PHP_EOL;
            exit;
        }
    }
}
