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
    private static ?CommentManager $instance = null;

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
    public static function getInstance(): CommentManager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * List all comments.
     *
     * @return Comment[]
     * @throws PDOException If the query execution fails.
     */
    public function listComments(): array
    {
        $db = DB::getInstance();

        try {
            $rows = $db->select('SELECT * FROM `comment`');

            $comments = [];
            foreach ($rows as $row) {
                $comment = new Comment();
                $comments[] = $comment->setId((int) $row['id']) // Casting to ensure type safety
                    ->setBody($row['body'])
                    ->setCreatedAt($row['created_at'])
                    ->setNewsId((int) $row['news_id']); // Casting to ensure type safety
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
    public function addCommentForNews(string $body, int $newsId): int
    {
        // Input validation
        if (empty($body) || $newsId <= 0) {
            throw new \InvalidArgumentException("Invalid comment data.");
        }

        $db = DB::getInstance();
        $sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES(:body, :created_at, :news_id)";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':body' => $body,
                ':created_at' => date('Y-m-d'),
                ':news_id' => $newsId
            ]);

            return (int) $db->lastInsertId();
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
    public function deleteComment(int $id): int
    {
        // Input validation
        if ($id <= 0) {
            throw new \InvalidArgumentException("Invalid comment ID.");
        }

        $db = DB::getInstance();
        $sql = "DELETE FROM `comment` WHERE `id`=:id";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            // Handle deletion errors
            throw new PDOException("Failed to delete comment: " . $e->getMessage());
        }
    }
}
