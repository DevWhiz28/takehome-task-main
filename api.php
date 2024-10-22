<?php

use App\App;

require_once __DIR__ . '/vendor/autoload.php';

header('Content-Type: application/json');

$app = new App();

// Handle API requests
if (!isset($_GET['title']) && !isset($_GET['prefixsearch'])) {
    // Return all articles
    echo json_encode(['content' => $app->getListOfArticles()]);
} elseif (isset($_GET['prefixsearch'])) {
    // Return articles starting with the prefix
    $prefix = htmlentities($_GET['prefixsearch'], ENT_QUOTES, 'UTF-8');
    $articles = array_filter($app->getListOfArticles(), function ($article) use ($prefix) {
        return stripos($article, $prefix) === 0;
    });
    echo json_encode(['content' => $articles]);
} elseif (isset($_GET['title'])) {
    // Return specific article
    $title = htmlentities($_GET['title'], ENT_QUOTES, 'UTF-8');
    echo json_encode(['content' => $app->fetch($title)]);
}
