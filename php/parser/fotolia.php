<?php

/**
 * Fotolia parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
class CTFotolia extends CTParser
{

    const COPYRIGHT = '&copy; %author% - Fotolia.com';

    const BASE_URL = 'http://www.fotolia.com/id/';

    protected function parse($number)
    {
        $url = self::BASE_URL . $number;

        $item = parent::parse($number);
        $item['source'] = 'Fotolia';
        $item['publisher'] = 'Fotolia';
        $item['license'] = 'Royalty-free';
        $item['link'] = $url;

        $doc = new DOMDocument();
        $response = $this->curl($url);
        $html = @$doc->loadHTML(wp_remote_retrieve_body($response));
        if ($html) {
            $xpath = new DOMXPath($doc);

            $tags = $xpath->query("*/meta[@property='og:author']");
            if (!is_null($tags) && $tags->length > 0) {
                $item['author'] = $tags->item(0)->getAttribute('content');
            }
        }

        return $item;
    }

}
