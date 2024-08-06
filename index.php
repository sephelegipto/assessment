<?php

namespace App;

// Composer Autoloading:
// We use require_once __DIR__ . '/vendor/autoload.php'; to load all necessary classes automatically through Composer's autoload feature.
require_once __DIR__ . '/vendor/autoload.php';

use App\Utils\CommentManager;
use App\Utils\NewsManager;
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
            $comments = $this->commentManager->listComments();

            foreach ($newsArticles as $news) {
                echo("############ NEWS " . $news->getTitle() . " ############<br>");
                echo($news->getBody() . "<br>");

                foreach ($comments as $comment) {
                    if ($comment->getNewsId() == $news->getId()) {
                        echo("Comment " . $comment->getId() . " : " . $comment->getBody() . "<br>");
                    }
                }
            }
        } catch (Exception $e) {
            // Handle exceptions and display a meaningful error message
            echo "An error occurred while displaying news: " . $e->getMessage();
        }
    }
}

// Instantiate the NewsDisplay class and call the method to display news articles and their comments
$newsDisplay = new NewsDisplay(NewsManager::getInstance(), CommentManager::getInstance());
$newsDisplay->displayNewsWithComments();
