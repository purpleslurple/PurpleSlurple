<?php
// Get the text to search for and the URL to search in
$text = $_GET['text'];
$url = $_GET['url'];

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

// Get the contents of the URL
$response = file_get_contents($url, false, $context);

if (isset($text) && $text !== '') {
    // The text variable is not empty
    // Highlight the search text in the response
    $highlightedResponse = preg_replace("/$text/i", "<span style=\"background-color:yellow;\">$text</span>", $response);

    // Display the highlighted response
    echo $highlightedResponse;

    // Scroll to the first occurrence of the highlighted text
    echo "<script>var firstMatch = document.querySelector('span[style=\"background-color:yellow;\"]');if (firstMatch) firstMatch.scrollIntoView();</script>";
  } else {
    // The text variable is empty
    // PurpleSlurple code here...
    echo "'Slurple me!";
 }

?>
