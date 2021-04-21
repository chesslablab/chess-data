<?php

namespace ChessData\Tests;

use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
}
