<?php
$theurl = "https://docs.github.com/en/codespaces/getting-started/deep-dive";

// get the web page
// $html = file_get_contents($theurl);

// add purple numbers
// $modified_html = add_purple_numbers($html);

// display the slurped page
// echo $modified_html;

// function to add purple numbers to the page
// function add_purple_numbers($html) {
//     $dom = new DOMDocument();
//     $dom->loadHTML($html);
//     $xpath = new DOMXPath($dom);
//     $tags = ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'li'];
//     $i = 0;
//     foreach ($tags as $tag) {
//         foreach ($xpath->query('//'.$tag) as $node) {
//             $i++;
//             $anchor = $dom->createElement('a');
//             $anchor->setAttribute('name', 'purp'.$i);
//             $node->parentNode->insertBefore($anchor, $node);
//         }
//     }
//     return $dom->saveHTML();
// }

// for a given url
// find all p, h1-h6, and li tags
// add an anchor tag before each one
// the anchor tag has a name attribute of purp1, purp2, etc.
// the anchor tag is inserted before the tag
// the anchor tag text is the number associated with the tag name
// display the modified html

// Load the HTML from a URL
$html = file_get_contents($theurl);
$doc = new DOMDocument();
@$doc->loadHTML($html);

// Create a DOMXPath object to query the document
$xpath = new DOMXPath($doc);

// Find all p, h1-h6, and li tags
$tags = $xpath->query('//p | //h1 | //h2 | //h3 | //h4 | //h5 | //h6 | //li');

// Loop through each tag and add an anchor before it
$i = 1;
foreach ($tags as $tag) {
    $anchor = $doc->createElement('a', $i);
    $anchor->setAttribute('name', 'purp' . $i);
    $tag->parentNode->insertBefore($anchor, $tag);
    $i++;
}

// Display the modified HTML
echo $doc->saveHTML();


?>
