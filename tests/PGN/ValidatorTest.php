<?php
namespace PGNChess\Tests\PGN;

use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testColorThrowException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validator::color('green');
    }

    public function testColorIsOk()
    {
        $this->assertEquals(Symbol::WHITE, Validator::color(Symbol::WHITE));
        $this->assertEquals(Symbol::BLACK, Validator::color(Symbol::BLACK));
    }

    public function testSquareIntegerThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validator::square(9);
    }

    public function testSquareFloatThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validator::square(9.75);
    }

    public function testSquareA9ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validator::square('a9');
    }

    public function testSquareFooThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validator::square('foo');
    }

    public function testSquareIsOk()
    {
        $this->assertEquals(Validator::square('e4'), 'e4');
    }
}
