<?php

namespace App;

// Composer Autoloading:
// We use require_once __DIR__ . '/vendor/autoload.php'; to load all necessary classes automatically through Composer's autoload feature.
require_once __DIR__ . '/vendor/autoload.php';

use App\Utils\CommentManager;
use App\Utils\NewsManager;
use App\Utils\Log;
use Exception;

/**
 * Class NewsDisplay
 *
 * Handles the display of news articles and their associated comments.
 */
class NewsDisplay
{
    /**
     * @var NewsManager The manager for handling news articles.
     */
    private NewsManager $newsManager;

    /**
     * @var CommentManager The manager for handling comments.
     */
    private CommentManager $commentManager;

    /**
     * NewsDisplay constructor.
     *
     * @param NewsManager $newsManager
     * @param CommentManager $commentManager
     */
    public function __construct(NewsManager $newsManager, CommentManager $commentManager)
    {
        $this->newsManager = $newsManager;
        $this->commentManager = $commentManager;
    }

    /**
     * Display news articles with their associated comments.
     *
     * @return void
     */
    public function displayNewsWithComments(): void
    {
        try {
            $newsArticles = $this->newsManager->listNews();

            if (empty($newsArticles)) {
                echo "No news articles available.<br>";
                return;
            }

            foreach ($newsArticles as $news) {
                $this->displaySingleNewsWithComments($news);
            }
        } catch (Exception $e) {
            // Log the exception
            Log::getLogger()->error("An error occurred while displaying news: " . $e->getMessage());

            // Handle exceptions and display a meaningful error message
            echo "An error occurred while displaying news: " . $e->getMessage();
        }
    }

    /**
     * Display a single news article with its associated comments.
     *
     * @param \App\Class\News $news
     * @return void
     */
    private function displaySingleNewsWithComments($news): void
    {
        echo "############ NEWS " . htmlspecialchars($news->getTitle()) . " ############<br>";
        echo htmlspecialchars($news->getBody()) . "<br>";

        // Fetch comments only for the specific news article
        $comments = $this->commentManager->listCommentsForNews($news->getId());

        if (empty($comments)) {
            echo "No comments available.<br>";
            return;
        }

        foreach ($comments as $comment) {
            echo "Comment " . $comment->getId() . " : " . htmlspecialchars($comment->getBody()) . "<br>";
        }
    }
}

// Instantiate the NewsDisplay class and call the method to display news articles and their comments
$newsDisplay = new NewsDisplay(NewsManager::getInstance(), CommentManager::getInstance());
$newsDisplay->displayNewsWithComments();
