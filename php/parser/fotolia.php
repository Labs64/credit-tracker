<?php

/**
 * Fotolia parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
class Fotolia extends Parser
{

    const COPYRIGHT = '&copy; %author% - Fotolia.com';

    const FOTOLIA_BASE_URL = 'http://www.fotolia.com/id/';

    function __construct()
    {
        parent::__construct();
    }

    protected function parse($number)
    {
        $url = self::FOTOLIA_BASE_URL . $number;
        $response = $this->curl->get($url, $vars = array());

        $item = parent::parse($number);

        $item['source'] = 'Fotolia';
        // $item['author'] = $response->body;
        $item['author'] = 'author';
        $item['publisher'] = 'Fotolia';
        $item['license'] = 'RF';

        return $item;
    }

}
