<?php

/**
 * Unsplash parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
class CTUnsplash extends CTParser
{

    const COPYRIGHT = '&copy; %author% - unsplash.com';

    const BASE_URL = 'https://unsplash.com/photos/';

    protected function parse($number)
    {
        $url = self::BASE_URL . $number;

        $item = parent::parse($number);
        $item['source'] = 'Unsplash';
        $item['publisher'] = 'Unsplash';
        $item['license'] = 'Creative Commons Zero (CC0 1.0)';
        $item['link'] = $url;

        $doc = new DOMDocument();
        $response = $this->curl($url);
        $html = @$doc->loadHTML(wp_remote_retrieve_body($response));
        if ($html) {
            $xpath = new DOMXPath($doc);

            $tags = $xpath->query("*/meta[@property='og:url']");
            if (!is_null($tags) && $tags->length > 0) {
                $item['link'] = $tags->item(0)->getAttribute('content');
            }

            $tags = $xpath->query("*/meta[@property='og:title']");
            if (!is_null($tags) && $tags->length > 0) {
                $author = $tags->item(0)->getAttribute('content');
                $author = str_replace("Photo by ", "", $author);
                $author = str_replace(" | Unsplash", "", $author);
                $item['author'] = $author;
            }
        }

        return $item;
    }

}
