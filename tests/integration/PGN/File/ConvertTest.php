<?php

namespace PGNChess\Tests\Integration\PGN\File;

use PGNChess\Db\Pdo;
use PGNChess\Exception\PgnFileSyntaxException;
use PGNChess\PGN\File\Convert as PgnFileConvert;
use PGNChess\Tests\AbstractIntegrationTestCase;

class ConvertTest extends AbstractIntegrationTestCase
{
    /**
     * @dataProvider pgnData
     * @test
     */
    public function to_mysql_games($filename)
    {
        $sql = (new PgnFileConvert(self::DATA_FOLDER."/$filename"))->toMySqlScript();

        $this->assertTrue(strpos($sql, 'INSERT INTO games') === 0);
    }

    public function pgnData()
    {
        return [
            ['01-games.pgn']
        ];
    }
}
