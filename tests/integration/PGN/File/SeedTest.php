<?php

namespace PGNChess\Tests\Integration\PGN\File;

use PGNChess\Db\Pdo;
use PGNChess\Exception\PgnFileSyntaxException;
use PGNChess\PGN\File\Seed as PgnFileSeed;
use PGNChess\Tests\AbstractIntegrationTestCase;

class SeedTest extends AbstractIntegrationTestCase
{
    public static function setUpBeforeClass()
    {
        Pdo::getInstance()->query('TRUNCATE TABLE games');
    }

    /**
     * @dataProvider pgnData
     * @test
     */
    public function db($filename)
    {
        (new PgnFileSeed(self::DATA_FOLDER."/$filename"))->db();

        $result = Pdo::getInstance()->query('SELECT count(*) as count FROM games')->fetch(\PDO::FETCH_ASSOC);

        $this->assertEquals(512, $result['count']);
    }

    public function pgnData()
    {
        return [
            ['01-games.pgn']
        ];
    }
}
