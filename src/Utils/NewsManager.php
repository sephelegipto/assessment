<?php

namespace App\Utils;

use App\Class\News;
use PDOException;

/**
 * Class NewsManager
 *
 * Manages news articles and their associated comments.
 */
class NewsManager
{
    /**
     * @var NewsManager|null Singleton instance of NewsManager.
     */
    private static ?NewsManager $instance = null;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
    }

    /**
     * Get the singleton instance of NewsManager.
     *
     * @return NewsManager
     */
    public static function getInstance(): NewsManager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * List all news articles.
     *
     * @return News[]
     * @throws PDOException If the query execution fails.
     */
    public function listNews(): array
    {
        $db = DB::getInstance();

        try {
            $rows = $db->select('SELECT * FROM `news`');

            $news = [];
            foreach ($rows as $row) {
                $article = new News();
                $news[] = $article->setId((int) $row['id']) // Casting to ensure type safety
                    ->setTitle($row['title'])
                    ->setBody($row['body'])
                    ->setCreatedAt($row['created_at']);
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
        // Input validation
        if (empty($title) || empty($body)) {
            throw new \InvalidArgumentException("Invalid news data.");
        }

        $db = DB::getInstance();
        $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES(:title, :body, :created_at)";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':body' => $body,
                ':created_at' => date('Y-m-d')
            ]);

            $newsId = (int) $db->lastInsertId();

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
        // Input validation
        if ($id <= 0) {
            throw new \InvalidArgumentException("Invalid news ID.");
        }

        $db = DB::getInstance();

        try {
            // Begin transaction
            $db->beginTransaction();

            // Fetch and delete comments only for the specific news article
            $comments = CommentManager::getInstance()->listCommentsForNews($id);
            foreach ($comments as $comment) {
                CommentManager::getInstance()->deleteComment($comment->getId());
            }

            $sql = "DELETE FROM `news` WHERE `id`=:id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $affectedRows = $stmt->rowCount();

            // Commit transaction
            $db->commit();

            // Log successful news deletion
            Log::getLogger()->info("Deleted news article ID $id and its linked comments.");

            return $affectedRows;
        } catch (PDOException $e) {
            // Rollback transaction in case of error
            $db->rollBack();

            // Log deletion errors
            Log::getLogger()->error("Failed to delete news article ID $id and its linked comments: " . $e->getMessage());
            throw new PDOException("Failed to delete news article ID $id and its linked comments: " . $e->getMessage());
        }
    }
}
