<?php

namespace App\Utils;

use App\Class\Comment;
use PDOException;

class CommentManager
{
	private static $instance = null;

	private function __construct()
	{
	}

	public static function getInstance()
	{
		if (null === self::$instance) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	public function listComments()
	{
		$db = DB::getInstance();

		try {
			$rows = $db->select('SELECT * FROM `comment`');

			$comments = [];
			foreach ($rows as $row) {
				$n = new Comment();
				$comments[] = $n->setId($row['id'])
					->setBody($row['body'])
					->setCreatedAt($row['created_at'])
					->setNewsId($row['news_id']);
			}

			return $comments;
		} catch (PDOException $e) {
			// Handle query execution errors
			throw new PDOException("Failed to list comments: " . $e->getMessage());
		}
	}

	public function addCommentForNews($body, $newsId)
	{
		$db = DB::getInstance();
		$sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES('" . $body . "','" . date('Y-m-d') . "','" . $newsId . "')";

		try {
			$db->exec($sql);
			return $db->lastInsertId($sql);
		} catch (PDOException $e) {
			// Handle insertion errors
			throw new PDOException("Failed to add comment: " . $e->getMessage());
		}
	}

	public function deleteComment($id)
	{
		$db = DB::getInstance();
		$sql = "DELETE FROM `comment` WHERE `id`=" . $id;

		try {
			return $db->exec($sql);
		} catch (PDOException $e) {
			// Handle deletion errors
			throw new PDOException("Failed to delete comment: " . $e->getMessage());
		}
	}
}
