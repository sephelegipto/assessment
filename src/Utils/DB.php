<?php

namespace App\Utils;

use Dotenv\Dotenv;
use PDO;
use PDOException;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

class DB
{
	private $pdo;

	private static $instance = null;

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

	public static function getInstance()
	{
		if (null === self::$instance) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

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

	public function exec($sql)
	{
		try {
			return $this->pdo->exec($sql);
		} catch (PDOException $e) {
			// Handle statement execution errors
			throw new PDOException("Statement execution failed: " . $e->getMessage());
		}
	}

	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}
}
