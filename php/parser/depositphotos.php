<?php

/**
 * Depositphotos parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
class CTDepositphotos extends CTParser
{

    const COPYRIGHT = '&copy; %author% /Depositphotos.com';

    const BASE_URL = 'https://depositphotos.com/';

    protected function parse($number)
    {
        $url = self::BASE_URL . $number;

        $item = parent::parse($number);
        $item['source'] = 'Depositphotos';
        $item['publisher'] = 'Depositphotos';
        $item['license'] = 'Royalty-free';
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

            $tags = $xpath->query("//span[@itemprop='name']");
            if (!is_null($tags) && $tags->length > 0) {
                $item['author'] = $tags->item(1)->textContent;
            }
        }

        return $item;
    }

}
