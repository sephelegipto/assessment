<?php

namespace App\Utils;

use App\Class\Comment;
use PDOException;

/**
 * Class CommentManager
 *
 * Manages comments for news articles.
 */
class CommentManager
{
    /**
     * @var CommentManager|null Singleton instance of CommentManager.
     */
    private static $instance = null;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
    }

    /**
     * Get the singleton instance of CommentManager.
     *
     * @return CommentManager
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
     * List all comments.
     *
     * @return Comment[]
     * @throws PDOException If the query execution fails.
     */
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

    /**
     * Add a comment to a news article.
     *
     * @param string $body The body of the comment.
     * @param int $newsId The ID of the associated news article.
     * @return int The ID of the newly inserted comment.
     * @throws PDOException If the insertion fails.
     */
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

    /**
     * Delete a comment by ID.
     *
     * @param int $id The ID of the comment to delete.
     * @return int The number of affected rows.
     * @throws PDOException If the deletion fails.
     */
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
