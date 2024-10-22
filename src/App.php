<?php

namespace App;

// Load global configurations or dependencies
require_once dirname(__DIR__) . '/globals.php';

/**
 * App class handles article-related operations such as save, update, fetch, and list retrieval.
 */
class App {

    /**
     * Save an article to the specified file path.
     *
     * @param string $title The file path or title where the article is saved.
     * @param string $body The content of the article.
     * @return void
     */
    public function save($title, $body) {
        error_log("Saving article $title, success!");
        file_put_contents($title, $body);
    }

    /**
     * Update an existing article by calling the save method.
     *
     * @param string $title The file path or title where the article is saved.
     * @param string $body The updated content of the article.
     * @return void
     */
    public function update($title, $body) {
        $this->save($title, $body);
    }

    /**
     * Fetch the content of an article.
     *
     * @param array $get The GET parameters array which should contain the 'title' key.
     * @return string The content of the requested article.
     */
    public function fetch($get) {
        $title = $get['title'] ?? null;
        if (!$title) {
            return '';
        }
        return file_get_contents(sprintf('articles/%s', $title));
    }

    /**
     * Retrieve a list of all articles in the articles directory.
     *
     * @return array List of article filenames.
     */
    public function getListOfArticles() {
        global $wgBaseArticlePath;
        return array_diff(scandir($wgBaseArticlePath), ['.', '..', '.DS_Store']);
    }
}
