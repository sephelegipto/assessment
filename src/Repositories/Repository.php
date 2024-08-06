<?php

namespace App\Repositories;

use App\Utils\DB;
use App\Class\Comment;
use App\Class\News;
use App\Utils\Log;
use PDOException;

/**
 * Class Repository
 *
 * Handles database operations for news articles and comments.
 */
class Repository
{
    private DB $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    /**
     * List all news articles.
     *
     * @return News[]
     * @throws PDOException If the query execution fails.
     */
    public function listNews(): array
    {
        try {
            $rows = $this->db->select('SELECT * FROM `news`');

            $news = [];
            foreach ($rows as $row) {
                $news[] = new News(
                    (int)$row['id'],
                    $row['title'],
                    $row['body'],
                    $row['created_at']
                );
            }

            // Log successful news listing
            Log::getLogger()->info("Listed news articles.");

            return $news;
        } catch (PDOException $e) {
            // Log query execution errors
            Log::getLogger()->error("Failed to list news: " . $e->getMessage());
            throw new PDOException("Failed to list news: " . $e->getMessage());
        }
    }

    /**
     * Add a news article.
     *
     * @param string $title The title of the news article.
     * @param string $body The body content of the news article.
     * @return int The ID of the newly inserted news article.
     * @throws PDOException If the insertion fails.
     */
    public function addNews(string $title, string $body): int
    {
        try {
            $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES(:title, :body, :created_at)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':body' => $body,
                ':created_at' => date('Y-m-d')
            ]);

            $newsId = (int)$this->db->lastInsertId();

            // Log successful news addition
            Log::getLogger()->info("Added news article ID $newsId.");

            return $newsId;
        } catch (PDOException $e) {
            // Log insertion errors
            Log::getLogger()->error("Failed to add news article: " . $e->getMessage());
            throw new PDOException("Failed to add news article: " . $e->getMessage());
        }
    }

    /**
     * Delete a news article and its linked comments.
     *
     * @param int $id The ID of the news article to delete.
     * @return int The number of affected rows.
     * @throws PDOException If the deletion fails.
     */
    public function deleteNews(int $id): int
    {
        try {
            $this->db->beginTransaction();

            // Delete comments linked to the news article
            $this->deleteCommentsByNewsId($id);

            $sql = "DELETE FROM `news` WHERE `id`=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);

            $affectedRows = $stmt->rowCount();
            $this->db->commit();

            // Log successful news deletion
            Log::getLogger()->info("Deleted news article ID $id and its linked comments.");

            return $affectedRows;
        } catch (PDOException $e) {
            $this->db->rollBack();
            // Log deletion errors
            Log::getLogger()->error("Failed to delete news article ID $id and its linked comments: " . $e->getMessage());
            throw new PDOException("Failed to delete news article ID $id and its linked comments: " . $e->getMessage());
        }
    }

    /**
     * Delete comments by news article ID.
     *
     * @param int $newsId The ID of the news article.
     * @throws PDOException If the deletion fails.
     */
    private function deleteCommentsByNewsId(int $newsId): void
    {
        try {
            $sql = "DELETE FROM `comment` WHERE `news_id`=:news_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':news_id' => $newsId]);

            // Log successful comments deletion
            Log::getLogger()->info("Deleted comments for news article ID $newsId.");
        } catch (PDOException $e) {
            // Log deletion errors
            Log::getLogger()->error("Failed to delete comments for news article ID $newsId: " . $e->getMessage());
            throw new PDOException("Failed to delete comments for news article ID $newsId: " . $e->getMessage());
        }
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
        try {
            $stmt = $this->db->prepare('SELECT * FROM `comment` WHERE `news_id` = :news_id');
            $stmt->execute([':news_id' => $newsId]);
            $rows = $stmt->fetchAll();

            $comments = [];
            foreach ($rows as $row) {
                $comments[] = new Comment(
                    (int)$row['id'],
                    $row['body'],
                    $row['created_at'],
                    (int)$row['news_id']
                );
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
        try {
            $sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES(:body, :created_at, :news_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':body' => $body,
                ':created_at' => date('Y-m-d'),
                ':news_id' => $newsId
            ]);

            $commentId = (int)$this->db->lastInsertId();

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
        try {
            $sql = "DELETE FROM `comment` WHERE `id`=:id";
            $stmt = $this->db->prepare($sql);
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
