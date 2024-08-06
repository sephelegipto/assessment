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
    private PDO $pdo;

    /**
     * @var DB|null Singleton instance of DB.
     */
    private static ?DB $instance = null;

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
    public static function getInstance(): DB
    {
        if (self::$instance === null) {
            self::$instance = new self();
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
    public function select(string $sql): array
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
    public function exec(string $sql): int
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
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Prepare a SQL statement for execution.
     *
     * @param string $sql The SQL query to prepare.
     * @return \PDOStatement The prepared statement.
     * @throws PDOException If preparation fails.
     */
    public function prepare(string $sql): \PDOStatement
    {
        try {
            return $this->pdo->prepare($sql);
        } catch (PDOException $e) {
            // Handle preparation errors
            throw new PDOException("Statement preparation failed: " . $e->getMessage());
        }
    }

    /**
     * Begin a transaction.
     *
     * @throws PDOException If the transaction start fails.
     */
    public function beginTransaction(): void
    {
        try {
            $this->pdo->beginTransaction();
        } catch (PDOException $e) {
            // Handle transaction errors
            throw new PDOException("Transaction start failed: " . $e->getMessage());
        }
    }

    /**
     * Commit a transaction.
     *
     * @throws PDOException If the transaction commit fails.
     */
    public function commit(): void
    {
        try {
            $this->pdo->commit();
        } catch (PDOException $e) {
            // Handle transaction commit errors
            throw new PDOException("Transaction commit failed: " . $e->getMessage());
        }
    }

    /**
     * Rollback a transaction.
     *
     * @throws PDOException If the transaction rollback fails.
     */
    public function rollBack(): void
    {
        try {
            $this->pdo->rollBack();
        } catch (PDOException $e) {
            // Handle transaction rollback errors
            throw new PDOException("Transaction rollback failed: " . $e->getMessage());
        }
    }
}
