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
    protected int $id;

    /**
     * @var string The title of the news article.
     */
    protected string $title;

    /**
     * @var string The body content of the news article.
     */
    protected string $body;

    /**
     * @var string The date and time when the news article was created.
     */
    protected string $createdAt;

    /**
     * Set the news article ID.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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
     * Set the title of the news article.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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
     * Set the body content of the news article.
     *
     * @param string $body
     * @return $this
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
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
     * Set the creation date and time of the news article.
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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
