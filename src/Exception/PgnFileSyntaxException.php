<?php

namespace ChessData\Exception;

/**
 * Thrown when a pgn file syntax exception occurs.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @license GPL
 */
final class PgnFileSyntaxException extends \InvalidArgumentException
{
    private $result;

    public function __construct(string $message, \stdClass $result)
    {
        $this->result = $result;
    }

    public function getResult(): \stdClass
    {
        return $this->result;
    }
}
