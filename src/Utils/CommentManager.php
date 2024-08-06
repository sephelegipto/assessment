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
     * List all comments for a specific news article.
     *
     * @param int $newsId The ID of the news article.
     * @return Comment[]
     * @throws PDOException If the query execution fails.
     */
    public function listCommentsForNews(int $newsId): array
    {
        $db = DB::getInstance();

        try {
            // Fetch comments only for the specific news article
            $stmt = $db->prepare('SELECT * FROM `comment` WHERE `news_id` = :news_id');
            $stmt->execute([':news_id' => $newsId]);
            $rows = $stmt->fetchAll();

            $comments = [];
            foreach ($rows as $row) {
                $comment = new Comment();
                $comments[] = $comment->setId((int) $row['id']) // Casting to ensure type safety
                    ->setBody($row['body'])
                    ->setCreatedAt($row['created_at'])
                    ->setNewsId((int) $row['news_id']); // Casting to ensure type safety
            }

            // Log successful comment listing
            Log::getLogger()->info("Listed comments for news ID $newsId.");

            return $comments;
        } catch (PDOException $e) {
            // Log query execution errors
            Log::getLogger()->error("Failed to list comments for news ID $newsId: " . $e->getMessage());
            throw new PDOException("Failed to list comments for news ID $newsId: " . $e->getMessage());
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

            $commentId = (int) $db->lastInsertId();

            // Log successful comment addition
            Log::getLogger()->info("Added comment ID $commentId for news ID $newsId.");

            return $commentId;
        } catch (PDOException $e) {
            // Log insertion errors
            Log::getLogger()->error("Failed to add comment for news ID $newsId: " . $e->getMessage());
            throw new PDOException("Failed to add comment for news ID $newsId: " . $e->getMessage());
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
            $affectedRows = $stmt->rowCount();

            // Log successful comment deletion
            Log::getLogger()->info("Deleted comment ID $id.");

            return $affectedRows;
        } catch (PDOException $e) {
            // Log deletion errors
            Log::getLogger()->error("Failed to delete comment ID $id: " . $e->getMessage());
            throw new PDOException("Failed to delete comment ID $id: " . $e->getMessage());
        }
    }
}
