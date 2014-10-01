<?php

/**
 * Freeimages parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
class CTFreeimages extends CTParser
{

    const COPYRIGHT = '&copy; %author% / <a href="http://www.freeimages.com" target="_blank">freeimages.com</a>';

    const BASE_URL = 'http://www.freeimages.com/photo/';

    protected function parse($number)
    {
        $url = self::BASE_URL . $number;

        $item = parent::parse($number);
        $item['source'] = 'Freeimages';
        $item['publisher'] = 'Freeimages';
        $item['license'] = 'Royalty-free';
        $item['link'] = $url;

        $doc = new DOMDocument();
        $html = @$doc->loadHTML($this->curl($url));
        if ($html) {
            $xpath = new DOMXPath($doc);

            $tags = $xpath->query("//tr/td[contains(., 'Uploaded by')]/a");
            if (!is_null($tags) && $tags->length > 0) {
                $item['author'] = $tags->item(0)->textContent;
            }
        }

        return $item;
    }

}
