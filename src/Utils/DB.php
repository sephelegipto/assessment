<?php

namespace App\Utils;

use Dotenv\Dotenv;
use PDO;
use PDOException;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

/**
 * Class DB
 *
 * Singleton class for database connection and operations.
 */
class DB
{
    /**
     * @var PDO The PDO instance for database connection.
     */
    private $pdo;

    /**
     * @var DB|null Singleton instance of DB.
     */
    private static $instance = null;

    /**
     * Private constructor to prevent direct instantiation.
     *
     * Initializes the PDO connection.
     *
     * @throws PDOException If the connection fails.
     */
    private function __construct()
    {
        $dsn = $_ENV['DB_DSN'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];

        try {
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle connection errors
            throw new PDOException("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Get the singleton instance of DB.
     *
     * @return DB
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    /**
     * Execute a SELECT query and return the results.
     *
     * @param string $sql The SQL query to execute.
     * @return array The result set as an array.
     * @throws PDOException If the query execution fails.
     */
    public function select($sql)
    {
        try {
            $sth = $this->pdo->query($sql);
            return $sth->fetchAll();
        } catch (PDOException $e) {
            // Handle query execution errors
            throw new PDOException("Query execution failed: " . $e->getMessage());
        }
    }

    /**
     * Execute a SQL statement and return the number of affected rows.
     *
     * @param string $sql The SQL statement to execute.
     * @return int The number of affected rows.
     * @throws PDOException If the statement execution fails.
     */
    public function exec($sql)
    {
        try {
            return $this->pdo->exec($sql);
        } catch (PDOException $e) {
            // Handle statement execution errors
            throw new PDOException("Statement execution failed: " . $e->getMessage());
        }
    }

    /**
     * Get the ID of the last inserted row.
     *
     * @return string The last insert ID.
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
