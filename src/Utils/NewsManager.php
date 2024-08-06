<?php

namespace App\Utils;

use App\Class\News;
use PDOException;

class NewsManager
{
    private static $instance = null;

    private function __construct()
    {
        // Autoloading through Composer makes manual includes unnecessary
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    /**
     * List all news
     */
    public function listNews()
    {
        $db = DB::getInstance();

        try {
            $rows = $db->select('SELECT * FROM `news`');

            $news = [];
            foreach ($rows as $row) {
                $n = new News();
                $news[] = $n->setId($row['id'])
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
     * Add a record in the news table
     */
    public function addNews($title, $body)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES('" . $title . "','" . $body . "','" . date('Y-m-d') . "')";

        try {
            $db->exec($sql);
            return $db->lastInsertId($sql);
        } catch (PDOException $e) {
            // Handle insertion errors
            throw new PDOException("Failed to add news: " . $e->getMessage());
        }
    }

    /**
     * Deletes a news article and also linked comments
     */
    public function deleteNews($id)
    {
        $comments = CommentManager::getInstance()->listComments();
        $idsToDelete = [];

        foreach ($comments as $comment) {
            if ($comment->getNewsId() == $id) {
                $idsToDelete[] = $comment->getId();
            }
        }

        try {
            foreach ($idsToDelete as $id) {
                CommentManager::getInstance()->deleteComment($id);
            }

            $db = DB::getInstance();
            $sql = "DELETE FROM `news` WHERE `id`=" . $id;

            return $db->exec($sql);
        } catch (PDOException $e) {
            // Handle deletion errors
            throw new PDOException("Failed to delete news and its linked comments: " . $e->getMessage());
        }
    }
}