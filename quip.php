<?php
// Get the URL to search in...
if (isset($_GET['url'])) {
    $url = $_GET['url'];
} else {
    $url = '';
}


// Get the text to search for...
if (isset($_GET['text'])) {
    $text = $_GET['text'];
} else {
    $text = '';
}


// Get the annotation...
if (isset($_GET['annotation'])) {
    $annotation = $_GET['annotation'];
} else {
    $annotation = '';
}


// If text, annotation and url are empty, show the welcome page
if (empty($text) && empty($annotation) && empty($url)) {
    echo show_welcome();
    exit;
}


// Get the webpage and highlight the text
function get_quip($url, $text, $annotation = 'No annotation provided') {
    // Set up the HTTP context options
    $options = array(
        'http' => array(
            'method' => 'GET',
            'header' => "Accept-language: en\r\n" .
                        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n"
        )
    );

    // Create the HTTP context
    $context = stream_context_create($options);

    // Get the contents of the webpage
    $webpage = file_get_contents($url, false, $context);

    // Highlight the search text in the webpage and insert $annotation as title attribute
    $highlightedResponse = str_replace($text, "<span style=\"background-color:yellow;\" title=\"$annotation\">$text</span>", $webpage);

    if ($highlightedResponse == $webpage) {
        echo "Selected text was not highlighted. QuiP does not process double quotes at this time. Please try again.";
    }

    // Add the url as base href to the webpage
    $highlightedResponse = str_ireplace("<head>", "<head><base href=\"$url\">", $highlightedResponse);

    // Add a disclaimer to the webpage at the top
    $highlightedResponse = "<h1>Disclaimer</h1><p>This page has been highlighted by <a href='https://purpleslurple.com/quip.php'>QuiP</a>. The original page is here: <a href=\"$url\">$url</a>.</p><hr>" . $highlightedResponse;

    // Scroll to the first occurrence of the highlighted text
    $highlightedResponse .= "<script>var firstMatch = document.querySelector('span[style=\"background-color:yellow;\"]');if (firstMatch) firstMatch.scrollIntoView();</script>";

    return $highlightedResponse;
}


// Show the welcome page with the bookmarklet
function show_welcome() {
    // Get the bookmarklet code and encode it for use in the link
    $bookmarkletCode = urlencode(file_get_contents('quip-bookmarklet.js')); 

    $welcome = '<h1>Welcome to QuiP</h1>
    <p>QuiP is a simple tool to highlight and annotate text on a webpage. Use it to easily share items of interest.</p>
    <p>QuiP Bookmarklet: <a href="' . $bookmarkletCode . '">QuiP</a>. Drag this link to your bookmarks bar to install the bookmarklet.</p>';

    return $welcome;
}


// Call the get_quip function with the parameters from the GET request
echo get_quip($url, $text, $annotation);
?>
