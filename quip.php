<?php
// Get the text to search for... 
If (isset($_GET['text'])) {
    $text = $_GET['text'];
} else {
    $text = '';
    echo "Text is not set";
    exit;
}
// Get the annotation parameter...
If (isset($_GET['annotation'])) {
    $annotation = $_GET['annotation'];
} else {
    $annotation = 'No annotation provided';
}

// and the URL to search in...
If (isset($_GET['url'])) {
    $url = $_GET['url'];
} else {
    $url = '';
    echo "URL is not set";
    exit;
}

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
$highlightedResponse = preg_replace("/$text/i", "<span style=\"background-color:yellow;\" title=\"$annotation\">$text</span>", $webpage);
// $highlightedResponse = preg_replace("/$text/i", "<span style=\"background-color:yellow;\">$text</span>", $webpage);

// Add the url as base href to the webpage
$highlightedResponse = str_replace("<head>", "<head><base href=\"$url\">", $highlightedResponse);
// Add a disclaimer to the webpage at the top
$highlightedResponse = "<h1>Disclaimer</h1><p>This is a highlighted version of the webpage at <a href=\"$url\">$url</a>. The highlighting is done by the server, not by the webpage itself.</p>" . $highlightedResponse;
// Display the highlighted webpage
echo $highlightedResponse;

// Scroll to the first occurrence of the highlighted text
echo "<script>var firstMatch = document.querySelector('span[style=\"background-color:yellow;\"]');if (firstMatch) firstMatch.scrollIntoView();</script>";

?>
