<?php

// TODO A: Improve the readability of this file through refactoring and documentation.

// TODO B: Review the HTML structure and make sure that it is valid and contains
// required elements. Edit and re-organize the HTML as needed.

// TODO C: Review the index.php entrypoint for security and performance concerns
// and provide fixes. Note any issues you don't have time to fix.

// TODO D: The list of available articles is hardcoded. Add code to get a
// dynamically generated list.

// TODO E: Are there performance problems with the word count function? How
// could you optimize this to perform well with large amounts of data? Code
// comments / psuedo-code welcome.

// TODO F: Implement a simple unit test to ensure the correctness of different parts
// of the application.

use App\App;

require_once __DIR__ . '/vendor/autoload.php';

$app = new App();

echo "<head>
<link rel='stylesheet' href='http://design.wikimedia.org/style-guide/css/build/wmui-style-guide.min.css'>
<link rel='stylesheet' href='styles.css'>
<script src='main.js'></script>
</head>";

$title = '';
$body = '';
$wordCount = '0 words written'; // Default word count when no article is selected

if (isset($_GET['title'])) {
    $title = htmlentities($_GET['title']);
    $body = $app->fetch($_GET);
    $body = file_get_contents(sprintf('articles/%s', $title));
    $wordCount = wfGetWc($body); // Get word count for the selected article
}

echo "<body>";
echo "<header>
<a href='/'>Article Editor</a>
<div class='word-count'>$wordCount</div>
</header>";

echo "<div class='page'>";

echo "<div class='sidebar'>
<h2>Articles</h2>
<div class='article-list'>
<ul>";
foreach ($app->getListOfArticles() as $article) {
    echo "<li><a href='index.php?title=" . htmlentities($article) . "'>" . htmlentities($article) . "</a></li>";
}
echo "</ul>
</div>
</div>";

echo "<div class='main-content'>
<h2>Create/Edit Article</h2>
<p>Create a new article by filling out the fields below. Edit an article by typing the beginning of the title in the title field, selecting the title from the auto-complete list, and changing the text in the textfield.</p>
<form action='index.php' method='post'>
<input name='title' type='text' placeholder='Article title...' value='$title'>
<br />
<textarea name='body' placeholder='Article body...'>$body</textarea>
<br />
<button type='submit'>Submit</button>
</form>";

echo "<div class='preview'>
<h2>Preview</h2>
<p><strong>$title</strong></p>
<p>$body</p>
</div>";

if ($_POST) {
    $app->save(sprintf("articles/%s", $_POST['title']), $_POST['body']);
}
echo "</div>";
echo "</div>";
echo "</body>";

/**
 * Returns the word count for the given content
 *
 * @param string $content The content of the article
 * @return string The word count in the format 'x words written'
 */
function wfGetWc($content) {
    if (empty($content)) {
        return "0 words written";
    }
    $wordCount = str_word_count($content);
    return "$wordCount words written";
}