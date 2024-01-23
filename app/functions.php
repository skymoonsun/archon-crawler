<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/simple_html_dom.php';

date_default_timezone_set('Europe/Istanbul');

function isXmlPage($url) {
    $headers = get_headers($url, 1);

    $contentType = isset($headers['Content-Type']) ? $headers['Content-Type'] : '';

    return strpos($contentType, 'xml') !== false;
}

function getNodeNames($sitemapUrl)
{
    $dom = new DOMDocument;
    $dom->load($sitemapUrl);

    $nodeNames = [];
    foreach ($dom->getElementsByTagName('*') as $element) {
        $nodeNames[] = $element->nodeName;
    }

    $repeatingDuals = array();

    for ($i = 0; $i < count($nodeNames) - 1; $i++) {
        $dual = $nodeNames[$i] . '-' . $nodeNames[$i + 1];

        if (!in_array($dual, $repeatingDuals) && count(array_keys($nodeNames, $nodeNames[$i])) > 1 && count(array_keys($nodeNames, $nodeNames[$i + 1])) > 1) {
            $repeatingDuals[] = $dual;
        }
    }

    $nodes = explode('-', $repeatingDuals[0]);

    return $nodes;
}

function getLinksFromSitemap($sitemapUrl)
{
    $nodes = getNodeNames($sitemapUrl);
    $firstNode = $nodes[0];
    $secondNode = $nodes[1];

    $xml = simplexml_load_file($sitemapUrl);

    $links = [];
    foreach ($xml->$firstNode as $sitemap) {
        $links[] = (string) $sitemap->$secondNode;
    }

    return $links;
}

function getAllLinksFromSitemapAndSubSitemaps($mainSitemapUrl)
{
    $allLinks = [];
    $mainSitemapLinks = getLinksFromSitemap($mainSitemapUrl);

    foreach ($mainSitemapLinks as $subSitemapUrl) {
        $subSitemapLinks = getLinksFromSitemap($subSitemapUrl);
        $allLinks = array_merge($allLinks, $subSitemapLinks);
    }

    return $allLinks;
}

function calculateWordCount($content)
{
    $content = strip_tags($content);
    $content = preg_replace('/\s+/', ' ', $content);
    $content = trim($content);
    $wordCount = str_word_count($content);

    return $wordCount;
}