<?php
namespace PGNChess\Tests\Piece;

use PGNChess\PGN\Symbol;
use PGNChess\Piece\Pawn;

class PawnTest extends \PHPUnit_Framework_TestCase
{
    public function testScopeWhite_a2()
    {
        $pawn = new Pawn(Symbol::WHITE, 'a2');
        
        $position = 'a2';
        $scope = (object) ['up' => ['a3', 'a4']];
        $captureSquares = ['b3'];    
        
        $this->assertEquals($position, $pawn->getPosition());
        $this->assertEquals($scope, $pawn->getScope());
        $this->assertEquals($captureSquares, $pawn->getCaptureSquares());
    }

    public function testScopeWhite_d5()
    {
        $pawn = new Pawn(Symbol::WHITE, 'd5');
        
        $position = 'd5';
        $scope = (object) ['up' => ['d6']];
        $captureSquares = ['c6', 'e6'];    

        $this->assertEquals($position, $pawn->getPosition());
        $this->assertEquals($scope, $pawn->getScope());
        $this->assertEquals($captureSquares, $pawn->getCaptureSquares());
    }

    public function testScopeWhite_f7()
    {
        $pawn = new Pawn(Symbol::WHITE, 'f7');
        
        $position = 'f7';
        $scope = (object) ['up' => ['f8']];
        $captureSquares = ['e8', 'g8'];  
        
        $this->assertEquals($position, $pawn->getPosition());
        $this->assertEquals($scope, $pawn->getScope());
        $this->assertEquals($captureSquares, $pawn->getCaptureSquares());
    }

    public function testScopeWhite_f8()
    {
        $pawn = new Pawn(Symbol::WHITE, 'f8');
        
        $position = 'f8';
        $scope = (object) ['up' => []];
        $captureSquares = [];  
        
        $this->assertEquals($position, $pawn->getPosition());
        $this->assertEquals($scope, $pawn->getScope());
        $this->assertEquals($captureSquares, $pawn->getCaptureSquares());
    }

    public function testScopeBlack_a2()
    {
        $pawn = new Pawn(Symbol::BLACK, 'a2');
        
        $position = 'a2';
        $scope = (object) ['up' => ['a1']];
        $captureSquares = ['b1'];  
        
        $this->assertEquals($position, $pawn->getPosition());
        $this->assertEquals($scope, $pawn->getScope());
        $this->assertEquals($captureSquares, $pawn->getCaptureSquares());
    }

    public function testScopeBlack_d5()
    {
        $pawn = new Pawn(Symbol::BLACK, 'd5');
        
        $position = 'd5';
        $scope = (object) ['up' => ['d4']];
        $captureSquares = ['c4', 'e4'];  
        
        $this->assertEquals($position, $pawn->getPosition());
        $this->assertEquals($scope, $pawn->getScope());
        $this->assertEquals($captureSquares, $pawn->getCaptureSquares());
    }
}
