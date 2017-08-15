<?php
namespace PGNChess\Tests\PGN;

use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validate;

class ValidateTest extends \PHPUnit_Framework_TestCase
{
    public function testColorThrowException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::color('green');
    }

    public function testColorIsOk()
    {
        $this->assertEquals(Symbol::WHITE, Validate::color(Symbol::WHITE));
        $this->assertEquals(Symbol::BLACK, Validate::color(Symbol::BLACK));
    }

    public function testSquareIntegerThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::square(9);
    }

    public function testSquareFloatThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::square(9.75);
    }

    public function testSquareA9ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::square('a9');
    }

    public function testSquareFooThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::square('foo');
    }

    public function testSquareIsOk()
    {
        $this->assertEquals(Validate::square('e4'), 'e4');
    }
}
