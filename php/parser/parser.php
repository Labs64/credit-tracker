<?php

/**
 * Base parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
abstract class CTParser
{
    /**
     * Execute parser on the selected agency and returns an array containing parsing result.
     *
     * @param string $number
     * @return agency parsing result
     **/
    public function execute($number)
    {
        return $this->parse($number);
    }

    /**
     * Returns array of media attributes.
     * Sample result attributes:
     *     $res = array(
     *         'ident_nr'       => '',
     *         'source'         => '',
     *         'author'         => '',
     *         'publisher'      => '',
     *         'license'        => '',
     *         'link'           => ''
     *     );
     *
     * This method should be overridden by every implementing class.
     *
     * @param string $number
     * @return agency parse result
     **/
    protected function parse($number)
    {
        $item = array();
        $item['ident_nr'] = $number;
        return $item;
    }

    /**
     * Wrapper around WP_HTTP object to allow simple get requests
     *
     * @param string $url
     * @param array|string $vars 
     * @return array ['headers','body','response','cookies']
     */
    protected function curl($url, $vars = array()){

        // Build GET string
        if (!empty($vars)) {
            $url .= (stripos($url, '?') !== false) ? '&' : '?';
            $url .= (is_string($vars)) ? $vars : http_build_query($vars, '', '&');
        }

        // Get contents via wordpress's http class
        return wp_remote_get($url);
    }

}
