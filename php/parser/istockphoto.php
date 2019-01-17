<?php

/**
 * iStockphoto parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
class CTIStockphoto extends CTParser
{

    const COPYRIGHT = '&copy;iStock.com/%author%';

    const BASE_URL = 'https://www.istockphoto.com/foto/';

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
          preg_match('<a class="photographer".*href=".*portfolio\/(.*)\?mediatype=photography">', wp_remote_retrieve_body($response), $matches);
          $item['author'] = $matches[1];
        }

        return $item;
    }

}
