<?php

namespace App;

class App {

    /**
     * Save an article to a file.
     * @param string $title The sanitized title.
     * @param string $body The sanitized body.
     * @return void
     */
    public function save($title, $body) {
        $filePath = $this->getFilePath($title);
        file_put_contents($filePath, $body); // Save article content to a file
    }

    /**
     * Fetch the content of the article.
     * @param string $title The sanitized title.
     * @return string
     */
    public function fetch($title) {
        $filePath = $this->getFilePath($title);
        return is_readable($filePath) ? file_get_contents($filePath) : ''; // Fetch file content if exists
    }

    /**
     * Return the list of available articles.
     * @return array
     */
    public function getListOfArticles() {
        $files = array_diff(scandir('articles'), ['.', '..', '.DS_Store']);
        return array_map(function ($file) {
            return pathinfo($file, PATHINFO_FILENAME);
        }, $files); // Return list of article names without file extensions
    }

    /**
     * Get the file path for the given title.
     * @param string $title The sanitized title.
     * @return string The full file path.
     */
    private function getFilePath($title) {
        return sprintf('articles/%s.txt', $title); // Store articles as .txt files
    }
}
