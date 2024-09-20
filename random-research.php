<?php
function getRandomLine($filename) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return $lines[array_rand($lines)];
}

function searchInternet($query) {
    $apiKey = '<your google API key>'; // Replace with your Google API key
    $cx = 'your custom search engine ID>'; // Replace with your Custom Search Engine ID
    $url = "https://www.googleapis.com/customsearch/v1?q=" . urlencode($query) . "&key=" . $apiKey . "&cx=" . $cx;
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function filterResults($results, $numResults = 10) {
    $filteredResults = array_filter($results['items'], function($result) {
        return !isset($result['pagemap']['metatags'][0]['og:type']) || $result['pagemap']['metatags'][0]['og:type'] !== 'advertisement';
    });
    shuffle($filteredResults);
    return array_slice($filteredResults, 0, $numResults);
}

$filename = 'items.txt'; // Replace with your file name
$randomItem = getRandomLine($filename);
$searchResults = searchInternet($randomItem);
$filteredResults = filterResults($searchResults, 15); // Change 15 to any number you want

echo "<html><body><h1>Search Results for '$randomItem'</h1><ul>";
foreach ($filteredResults as $result) {
    echo "<li><a href='" . $result['link'] . "' target='_blank'>" . $result['title'] . "</a></li>";
}
echo "</ul></body></html>";
?>
