<?php

use App\Utils\CommentManager;
use App\Utils\NewsManager;

// Composer Autoloading:
// We use require_once __DIR__ . '/vendor/autoload.php'; to load all necessary classes automatically through Composer's autoload feature.
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Displays news articles with their associated comments.
 */
function displayNewsWithComments(): void
{
    try {
        $newsManager = NewsManager::getInstance();
        $commentManager = CommentManager::getInstance();

		foreach ($newsManager->listNews() as $news) {
			echo("############ NEWS " . $news->getTitle() . " ############<br>");
			echo($news->getBody() . "<br>");
			foreach ($commentManager->listComments() as $comment) {
				if ($comment->getNewsId() == $news->getId()) {
					echo("Comment " . $comment->getId() . " : " . $comment->getBody() . "<br>");
				}
			}
		}

    } catch (Exception $e) {
        // Handle exceptions and display a meaningful error message
        echo "An error occurred: " . $e->getMessage();
    }
}

// Call the function to display news articles and their comments
displayNewsWithComments();
