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
                $news[] = $article->setId($row['id'])
                    ->setTitle($row['title'])
                    ->setBody($row['body'])
                    ->setCreatedAt($row['created_at']);
            }

            return $news;
        } catch (PDOException $e) {
            // Handle query execution errors
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
        $db = DB::getInstance();
        $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES(:title, :body, :created_at)";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':body' => $body,
                ':created_at' => date('Y-m-d')
            ]);

            return $db->lastInsertId();
        } catch (PDOException $e) {
            // Handle insertion errors
            throw new PDOException("Failed to add news: " . $e->getMessage());
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
        $comments = CommentManager::getInstance()->listComments();
        $idsToDelete = [];

        foreach ($comments as $comment) {
            if ($comment->getNewsId() === $id) {
                $idsToDelete[] = $comment->getId();
            }
        }

        try {
            foreach ($idsToDelete as $commentId) {
                CommentManager::getInstance()->deleteComment($commentId);
            }

            $db = DB::getInstance();
            $sql = "DELETE FROM `news` WHERE `id`=:id";

            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            // Handle deletion errors
            throw new PDOException("Failed to delete news and its linked comments: " . $e->getMessage());
        }
    }
}
