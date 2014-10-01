<?php

/**
 * Pixelio parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
class CTPixelio extends CTParser
{

    const COPYRIGHT = '&copy; %author% / <a href="http://www.pixelio.de" target="_blank">PIXELIO</a>';

    const BASE_URL = 'http://www.pixelio.de/media/';

    protected function parse($number)
    {
        $url = self::BASE_URL . $number;

        $item = parent::parse($number);
        $item['source'] = 'Pixelio';
        $item['publisher'] = 'Pixelio';
        $item['license'] = 'Royalty-free';
        $item['link'] = $url;

        $doc = new DOMDocument();
        $html = @$doc->loadHTML($this->curl($url));
        if ($html) {
            $xpath = new DOMXPath($doc);

            $tags = $xpath->query("//tr/td[contains(., 'Fotograf:')]/following::td[1]/a");
            if (!is_null($tags) && $tags->length > 0) {
                $item['author'] = $tags->item(0)->textContent;
            }
        }

        return $item;
    }

}
