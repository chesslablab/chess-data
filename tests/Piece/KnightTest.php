<?php
namespace PGNChess\Tests\Piece;

use PGNChess\PGN;
use PGNChess\Piece\Knight;

class KnightTest extends \PHPUnit_Framework_TestCase
{
  public function testInstantiate()
  {
    $knight = new Knight(PGN::COLOR_WHITE, 'b1');
    $this->assertInstanceOf(Knight::class, $knight);
  }
}
