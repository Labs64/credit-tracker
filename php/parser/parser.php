<?php

/**
 * Base parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
abstract class Parser
{
    protected $curl = null;

    function __construct()
    {
        $this->curl = new Curl();
    }

    /**
     * Execute parser on the selected agency and returns a object containing parsing result.
     *
     * @param string $number
     * @return agency parsing result
     **/
    public function execute($number)
    {
        return $this->parse($number);
    }

    /**
     * This method should be overridden by every implementing class.
     *
     * @param string $number
     * @return agency parse result
     **/
    protected function parse($number)
    {
        $item = array();
        $item['number'] = $number;
        return $item;
    }

}
