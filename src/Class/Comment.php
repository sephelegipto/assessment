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
    protected $id;

    /**
     * @var string The body of the comment.
     */
    protected $body;

    /**
     * @var string The date and time when the comment was created.
     */
    protected $createdAt;

    /**
     * @var int The ID of the associated news article.
     */
    protected $newsId;

    /**
     * Set the comment ID.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the comment ID.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the body of the comment.
     *
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get the body of the comment.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the creation date and time of the comment.
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the creation date and time of the comment.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the news article ID associated with the comment.
     *
     * @param int $newsId
     * @return $this
     */
    public function setNewsId($newsId)
    {
        $this->newsId = $newsId;

        return $this;
    }

    /**
     * Get the news article ID associated with the comment.
     *
     * @return int
     */
    public function getNewsId()
    {
        return $this->newsId;
    }
}
