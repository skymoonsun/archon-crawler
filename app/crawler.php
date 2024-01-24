<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/dbOP/Crawler.class.php';

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
    'http' => array(
        'method' => 'GET',
        'timeout' => 30,
        'ignore_errors' => true,
    ));

$mainSitemapUrl = 'https://ruyalar.net/wp-sitemap.xml';

$links = getAllLinksFromSitemapAndSubSitemaps($mainSitemapUrl);

foreach ($links as $link){
    $content = @str_get_html(file_get_contents($link, false, stream_context_create($arrContextOptions)));
    $results = $content->find("div.entry-content", 0);

    $wordCount = 0;
    if(is_object($results)) {
        $wordCount = calculateWordCount($results->plaintext);
    }
    $createdAt = date('Y-m-d H:i:s');

    $crawler = new Crawler();

    $crawler->url = $link;
    $crawler->word_count = $wordCount;
    $crawler->created_at = $createdAt;

    $crawler->create();
}

echo "Done!";