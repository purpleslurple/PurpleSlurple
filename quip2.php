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

    // If the highlighted response is the same as the original webpage, the text was not found, so no replacement was made
    if ($highlightedResponse == $webpage) {
        echo "Selected text was not highlighted. QuiP does not process double quotes at this time. Please try again.";
    
        // Log the parameters and webpage to a file
        $log = date('Y-m-d H:i:s') . "\t" . $url . "\t" . $text . "\t" . $annotation . "\t" . $webpage . "\n";
        file_put_contents('quip.log', $log, FILE_APPEND);
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
    $bookmarkletCode = urlencode(file_get_contents('quip-bookmarklet.js')); // File should be single line of code and URL encoded

    $welcome = '<h1>Welcome to QuiP</h1>
    <p>QuiP is a simple tool to highlight and annotate text on a webpage. Use it to easily share items of interest.</p>
    <p>QuiP Bookmarklet: <a href="<?php echo $bookmarkletCode; ?>">QuiP</a>. Drag this link to your bookmarks bar to install the bookmarklet.</p>';

    return $welcome;
}


// Call the get_quip function with the parameters from the GET request
echo get_quip($url, $text, $annotation);
?>