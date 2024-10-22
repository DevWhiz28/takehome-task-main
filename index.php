<?php

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
$wordCount = '0 words written';

// Sanitize the 'title' input and retrieve the article if available
if (isset($_GET['title'])) {
    $title = htmlentities($_GET['title'], ENT_QUOTES, 'UTF-8');
    $body = $app->fetch($title); // Fetch sanitized
    $wordCount = wfGetWc($body); // Calculate word count
}

echo "<body>";
echo "<header>
<a href='/'>Article Editor</a>
<div class='word-count'>$wordCount</div>
</header>";

echo "<div class='page'>";

// Sidebar with list of articles
echo "<div class='sidebar'>
<h2>Articles</h2>
<div class='article-list'>
<ul>";
foreach ($app->getListOfArticles() as $article) {
    echo "<li><a href='index.php?title=" . htmlentities($article, ENT_QUOTES, 'UTF-8') . "'>" . htmlentities($article, ENT_QUOTES, 'UTF-8') . "</a></li>";
}
echo "</ul>
</div>
</div>";

// Main content for article creation/editing
echo "<div class='main-content'>
<h2>Create/Edit Article</h2>
<p>Create or edit an article by typing the title and body below.</p>
<form action='index.php' method='post'>
<input name='title' type='text' placeholder='Article title...' value='$title' required>
<br />
<textarea name='body' placeholder='Article body...'>$body</textarea>
<br />
<button type='submit'>Submit</button>
</form>";

// Preview the article
echo "<div class='preview'>
<h2>Preview</h2>
<p><strong>$title</strong></p>
<p>$body</p>
</div>";

if ($_POST) {
    $sanitizedTitle = htmlentities($_POST['title'], ENT_QUOTES, 'UTF-8');
    $sanitizedBody = htmlentities($_POST['body'], ENT_QUOTES, 'UTF-8');
    $app->save($sanitizedTitle, $sanitizedBody); // Sanitize and save the article
}

echo "</div>";
echo "</div>";
echo "</body>";

/**
 * Optimized word count function.
 * @param string $content
 * @return string
 */
function wfGetWc($content) {
    $content = strip_tags($content); // Strip HTML tags to avoid counting them as words
    $wordCount = str_word_count($content);
    return "$wordCount words written";
}
