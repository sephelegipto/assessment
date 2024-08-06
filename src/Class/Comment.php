<?php

namespace App\Class;

/**
 * Class Comment
 *
 * Represents a comment on a news article.
 */
class Comment
{
    /**
     * @var int The comment ID.
     */
    private int $id;

    /**
     * @var string The body of the comment.
     */
    private string $body;

    /**
     * @var string The date and time when the comment was created.
     */
    private string $createdAt;

    /**
     * @var int The ID of the associated news article.
     */
    private int $newsId;

    /**
     * Constructor for the Comment class.
     *
     * @param int $id The comment ID.
     * @param string $body The body of the comment.
     * @param string $createdAt The creation date and time of the comment.
     * @param int $newsId The ID of the associated news article.
     */
    public function __construct(int $id, string $body, string $createdAt, int $newsId)
    {
        $this->id = $id;
        $this->body = $body;
        $this->createdAt = $createdAt;
        $this->newsId = $newsId;
    }

    /**
     * Get the comment ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the body of the comment.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Get the creation date and time of the comment.
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * Get the news article ID associated with the comment.
     *
     * @return int
     */
    public function getNewsId(): int
    {
        return $this->newsId;
    }
}
