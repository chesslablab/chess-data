<?php

namespace ChessData;

/**
 * Pdo class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Pdo
{
    /**
     * Reference to the Pdo instance.
     *
     * @var \Telecoming\Db\Pdo
     */
    private static $instance;

    /**
     * DSN.
     *
     * @var string
     */
    private $dsn;

    /**
     * PDO handler.
     *
     * @var PDO
     */
    private $pdo;

    /**
     * Returns the current instance.
     *
     * @return Singleton
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Constructor.
     */
    protected function __construct()
    {
        $this->dsn = $_ENV['DB_DRIVER'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'];
        $this->pdo = new \PDO(
            $this->dsn,
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD']
        );
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Prevents from cloning.
     */
    private function __clone()
    {
    }

    /**
     * Prevents from unserializing.
     */
    private function __wakeup()
    {
    }

    /**
     * Queries the database.
     *
     * @param string
     * @param array
     * @return bool
     */
    public function query($sql, $values = [])
    {
        $stmt = $this->pdo->prepare($sql);

        foreach ($values as $value) {
            $stmt->bindValue(
                $value['param'],
                $value['value'],
                $value['type'] ?? null
            );
        }

        $stmt->execute();

        return $stmt;
    }
}
