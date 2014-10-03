<?php

/**
 * iStockphoto parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
class CTIStockphoto extends CTParser
{

    const COPYRIGHT = '&copy;iStockphoto.com/%author%';

    const BASE_URL = 'http://www.istockphoto.com/id/';

    protected function parse($number)
    {
        $url = self::BASE_URL . $number;

        $item = parent::parse($number);
        $item['source'] = 'iStockphoto';
        $item['publisher'] = 'iStockphoto';
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
