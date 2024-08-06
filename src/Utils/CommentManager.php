<?php

namespace App\Utils;

use App\Repositories\Repository;
use App\Classes\Comment;
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

    private Repository $repository;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
        $this->repository = new Repository();
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
        return $this->repository->listCommentsForNews($newsId);
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
        if (empty($body) || $newsId <= 0) {
            throw new \InvalidArgumentException("Invalid comment data.");
        }

        return $this->repository->addCommentForNews($body, $newsId);
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
        if ($id <= 0) {
            throw new \InvalidArgumentException("Invalid comment ID.");
        }

        return $this->repository->deleteComment($id);
    }
}
