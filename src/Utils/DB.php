<?php

namespace App\Utils;

use Dotenv\Dotenv;

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

		$this->pdo = new \PDO($dsn, $user, $password);
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
		$sth = $this->pdo->query($sql);
		return $sth->fetchAll();
	}

	public function exec($sql)
	{
		return $this->pdo->exec($sql);
	}

	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}
}
