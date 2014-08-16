<?php

/**
 * Base parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
abstract class CTParser
{
    protected $curl = null;

    function __construct()
    {
        $this->curl = new Curl();
    }

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

}
