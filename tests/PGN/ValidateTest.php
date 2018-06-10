<?php
namespace PGNChess\Tests\PGN;

use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validate;
use PHPUnit\Framework\TestCase;

class ValidateTest extends TestCase
{
    /**
     * @test
     */
    public function color_green_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::color('green');
    }

    /**
     * @test
     */
    public function color_white()
    {
        $this->assertEquals(Symbol::WHITE, Validate::color(Symbol::WHITE));
    }

    /**
     * @test
     */
    public function color_black()
    {
        $this->assertEquals(Symbol::BLACK, Validate::color(Symbol::BLACK));
    }

    /**
     * @test
     */
    public function square_integer_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::square(9);
    }

    /**
     * @test
     */
    public function square_float_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::square(9.75);
    }

    /**
     * @test
     */
    public function square_a9_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::square('a9');
    }

    /**
     * @test
     */
    public function square_foo_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::square('foo');
    }

    /**
     * @test
     */
    public function square_e4()
    {
        $this->assertEquals(Validate::square('e4'), 'e4');
    }
}
