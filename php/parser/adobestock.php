<?php

/**
 * Adobe Stock parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
class CTAdobeStock extends CTParser
{

    const COPYRIGHT = '&copy; %author% - stock.adobe.com';

    const BASE_URL = 'https://stock.adobe.com/search?k=';

    protected function parse($number)
    {
        $url = self::BASE_URL . $number;

        $item = parent::parse($number);
        $item['source'] = 'Adobe Stock';
        $item['publisher'] = 'Adobe Stock';
        $item['license'] = 'Royalty-free';

        $doc = new DOMDocument();
        $response = $this->curl($url);
        $html = @$doc->loadHTML(wp_remote_retrieve_body($response));


        if ($html) {
            $xpath = new DOMXPath($doc);

            $tags = $xpath->query("//div[@id='image-detail-json']");
            if (!is_null($tags) && $tags->length > 0) {
                $image_detail_json = $tags->item(0)->textContent;

                $image_detail_array = json_decode($image_detail_json);

                $item['author'] = $image_detail_array->$number->author;
                $item['link'] = $image_detail_array->$number->content_url;
            }
        }

        return $item;
    }

}
