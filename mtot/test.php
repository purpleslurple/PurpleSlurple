<?php
$urls = array(
    "https://en.wikipedia.org/wiki/Deepfake",
    "https://www.feynmanlectures.caltech.edu/I_35.html",
    "https://thefutureoftext.org/team/",
    "https://www.thefutureoftext.org/symposium.html",
    // Add more URLs here
);

$totalUrls = count($urls);
$matchedCount = 0;

foreach ($urls as $url) {
    // Retrieve the web page content
    $webContent = file_get_contents($url);

    // Extract all words from the content
    $words = str_word_count(strip_tags($webContent), 1);

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
    echo "First Google result: $googleResultURL<br>";
    echo "Match condition: $matchCondition<br><br>";

    if ($matchCondition === "Match") {
        $matchedCount++;
    }
}

$matchPercentage = ($matchedCount / $totalUrls) * 100;
echo "Percentage matched: $matchPercentage%";
?>