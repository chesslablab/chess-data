<?php

namespace PGNChessData\Tests\Unit\File;

use PGNChessData\File\Validate as PgnFileValidate;
use PGNChessData\Tests\AbstractUnitTestCase;

class ValidateTest extends AbstractUnitTestCase
{
    /**
     * @dataProvider nonStrData
     * @test
     */
    public function non_str($filename, $invalid)
    {
        $result = (new PgnFileValidate(self::DATA_FOLDER."/non-str/$filename"))->syntax();

        $this->assertEquals($invalid, $result->total - $result->valid);
    }

    public function nonStrData()
    {
        return [
            ['01.pgn', 5],
            ['02.pgn', 17],
            ['03.pgn', 15],
        ];
    }

    /**
     * @dataProvider syntaxData
     * @test
     */
    public function syntax($filename)
    {
        $result = (new PgnFileValidate(self::DATA_FOLDER."/syntax/$filename"))->syntax();

        $this->assertTrue($result->valid > 0);
    }

    public function syntaxData()
    {
        return [
            ['01.pgn'],
            ['02.pgn'],
            ['03.pgn'],
        ];
    }

    /**
     * @dataProvider textData
     * @test
     */
    public function text($filename)
    {
        $result = (new PgnFileValidate(self::DATA_FOLDER."/text/$filename"))->syntax();

        $this->assertEquals(0, $result->valid);
    }

    public function textData()
    {
        return [
            ['01.pgn'],
            ['02.pgn'],
            ['03.pgn'],
        ];
    }

    /**
     * @dataProvider textWithNonStrData
     * @test
     */
    public function text_with_non_str($filename, $nErrors)
    {
        $result = (new PgnFileValidate(self::DATA_FOLDER."/text-with-non-str/$filename"))->syntax();

        $this->assertEquals(0, $result->valid);
        $this->assertEquals($nErrors, $result->total - $result->valid);
    }

    public function textWithNonStrData()
    {
        return [
            ['01.pgn', 1],
            ['02.pgn', 4],
            ['03.pgn', 2],
        ];
    }

    /**
     * @dataProvider withThreeInvalidData
     * @test
     */
    public function with_three_invalid($filename)
    {
        $result = (new PgnFileValidate(self::DATA_FOLDER."/with-three-invalid/$filename"))->syntax();

        $this->assertTrue($result->valid > 0);
        $this->assertEquals(3, $result->total - $result->valid);
    }

    public function withThreeInvalidData()
    {
        return [
            ['01.pgn'],
        ];
    }
}
