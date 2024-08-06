<?php

namespace App\Utils;

use App\Repositories\Repository;
use App\Classes\News;
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

    private Repository $repository;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
        $this->repository = new Repository();
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
        return $this->repository->listNews();
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
        if (empty($title) || empty($body)) {
            throw new \InvalidArgumentException("Invalid news data.");
        }

        return $this->repository->addNews($title, $body);
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
        if ($id <= 0) {
            throw new \InvalidArgumentException("Invalid news ID.");
        }

        return $this->repository->deleteNews($id);
    }
}
