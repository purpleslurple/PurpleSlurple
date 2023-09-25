<?php
$urls = array(
    "https://www.feynmanlectures.caltech.edu/I_35.html",
    "https://www.infoworld.com/article/3697653/when-the-rubber-duck-talks-back.html",
    "https://hacks.mozilla.org/2022/09/the-100-percent-markdown-expedition/",
    // Add more URLs here
);

$totalUrls = count($urls);
$matchedCount = 0;

foreach ($urls as $url) {
    // Retrieve the web page content
    $webContent = file_get_contents($url);

    // Create a DOMDocument object to parse the HTML
    $dom = new DOMDocument();
    @$dom->loadHTML($webContent);

    // Initialize an XPath object
    $xpath = new DOMXPath($dom);

    // Query to select all text nodes within the <p> tags
    $query = "//p//text()";

    $textNodes = $xpath->query($query);
    $articleText = "";

    // Combine all text nodes to form the article content
    foreach ($textNodes as $node) {
        $articleText .= $node->nodeValue . " ";
    }

    // Tokenize the article content into words
    $words = preg_split('/\s+/', $articleText, -1, PREG_SPLIT_NO_EMPTY);

    // Select a random starting point for 8 consecutive words
    $startIndex = mt_rand(0, count($words) - 8);

    // Get the next 8 consecutive words
    $randomString = implode(" ", array_slice($words, $startIndex, 8));

    // Wrap the random string in quotes
    $quotedString = '"' . $randomString . '"';

    // Send the query to Google
    $googleQuery = "https://www.google.com/search?q=" . urlencode($quotedString);

    // Get the URL of the first result from Google
    $googleResults = file_get_contents($googleQuery);
    preg_match('/<a href="\/url\?q=(.*?)&/', $googleResults, $match);
    $googleResultURL = urldecode($match[1]);

    // Compare the URLs
    $matchCondition = ($googleResultURL === $url) ? "Match" : "No match";

    // Print the results
    echo "Current URL: $url<br>";
    echo "Quoted String: $quotedString<br>";
    // display the Google query URL, and make it a link, open in new tab
    echo "Google query: <a href='$googleQuery' target='_blank'>$googleQuery</a><br>";
    echo "First Google result: $googleResultURL<br>";
    echo "Match condition: $matchCondition<br><br>";

    if ($matchCondition === "Match") {
        $matchedCount++;
    }
}

$matchPercentage = ($matchedCount / $totalUrls) * 100;
echo "Percentage matched: $matchPercentage%";
?>
