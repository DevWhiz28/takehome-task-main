<?php

// TODO A: Improve the readability of this file through refactoring and documentation.
// TODO B: Clean up the following code so that it's easier to see the different
// routes and handlers for the API, and simpler to add new ones.
// TODO C: Address performance concerns in the current code.
// that you would address during refactoring.
// TODO D: Identify any potential security vulnerabilities in this code.
// TODO E: Document this code to make it more understandable
// for other developers.

use App\App;

require_once __DIR__ . '/vendor/autoload.php';

// Initialize the application
$app = new App();

// Set content type to JSON
header('Content-Type: application/json');

// Handle different API requests
if (!isset($_GET['title']) && !isset($_GET['prefixsearch'])) {
    // Return list of all articles
    echo json_encode(['content' => $app->getListOfArticles()]);
} elseif (isset($_GET['prefixsearch'])) {
    // Return articles that start with the prefix
    $articles = array_filter(
        $app->getListOfArticles(),
        function($article) {
            return stripos($article, $_GET['prefixsearch']) === 0;
        }
    );
    echo json_encode(['content' => $articles]);
} else {
    // Fetch and return the requested article
    echo json_encode(['content' => $app->fetch($_GET)]);
}
