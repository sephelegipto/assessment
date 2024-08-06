<?php

namespace App\Class;

/**
 * Class News
 *
 * Represents a news article.
 */
class News
{
    /**
     * @var int The news article ID.
     */
    private int $id;

    /**
     * @var string The title of the news article.
     */
    private string $title;

    /**
     * @var string The body content of the news article.
     */
    private string $body;

    /**
     * @var string The date and time when the news article was created.
     */
    private string $createdAt;

    /**
     * Constructor for the News class.
     *
     * @param int $id The news article ID.
     * @param string $title The title of the news article.
     * @param string $body The body content of the news article.
     * @param string $createdAt The creation date and time of the news article.
     */
    public function __construct(int $id, string $title, string $body, string $createdAt)
    {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->createdAt = $createdAt;
    }

    /**
     * Get the news article ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the title of the news article.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the body content of the news article.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Get the creation date and time of the news article.
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
