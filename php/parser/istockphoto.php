<?php

/**
 * iStockphoto parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
class IStockphoto extends Parser
{

    const COPYRIGHT = '&copy;iStockphoto.com/%author%';

    const BASE_URL = 'http://www.istockphoto.com/id/';

    function __construct()
    {
        parent::__construct();
    }

    protected function parse($number)
    {
        $item = parent::parse($number);
        $item['source'] = 'iStockphoto';
        $item['publisher'] = 'iStockphoto';
        $item['license'] = 'Royalty-free';

        $url = self::BASE_URL . $number;
        $doc = new DOMDocument();
        $html = @$doc->loadHTMLFile($url);
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
