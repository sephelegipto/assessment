<?php

namespace App\Utils;

use PDO;
use PDOException;
use Dotenv\Dotenv;

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
        $dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
        $dotenv->load();

        $dsn = $_ENV['DB_DSN'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];

        try {
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Log successful connection
            Log::getLogger()->info("Database connection established.");
        } catch (PDOException $e) {
            // Log connection errors
            Log::getLogger()->error("Database connection failed: " . $e->getMessage());
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

            // Log the query
            Log::getLogger()->info("Executed SELECT query: $sql");

            return $sth->fetchAll();
        } catch (PDOException $e) {
            // Log query execution errors
            Log::getLogger()->error("Query execution failed: " . $e->getMessage());
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
            $result = $this->pdo->exec($sql);

            // Log the executed statement
            Log::getLogger()->info("Executed SQL statement: $sql");

            return $result;
        } catch (PDOException $e) {
            // Log statement execution errors
            Log::getLogger()->error("Statement execution failed: " . $e->getMessage());
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
            // Log the prepared statement
            Log::getLogger()->info("Prepared SQL statement: $sql");

            return $this->pdo->prepare($sql);
        } catch (PDOException $e) {
            // Log preparation errors
            Log::getLogger()->error("Statement preparation failed: " . $e->getMessage());
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

            // Log transaction start
            Log::getLogger()->info("Transaction started.");
        } catch (PDOException $e) {
            // Log transaction errors
            Log::getLogger()->error("Transaction start failed: " . $e->getMessage());
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

            // Log transaction commit
            Log::getLogger()->info("Transaction committed.");
        } catch (PDOException $e) {
            // Log transaction commit errors
            Log::getLogger()->error("Transaction commit failed: " . $e->getMessage());
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

            // Log transaction rollback
            Log::getLogger()->info("Transaction rolled back.");
        } catch (PDOException $e) {
            // Log transaction rollback errors
            Log::getLogger()->error("Transaction rollback failed: " . $e->getMessage());
            throw new PDOException("Transaction rollback failed: " . $e->getMessage());
        }
    }
}
