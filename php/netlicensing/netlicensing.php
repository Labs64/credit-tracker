<?php

/**
 * A basic NetLicensing client
 *
 * @package netlicensing
 * @author Labs64 <info@labs64.com>
 **/
class NetLicensing
{

    const NLIC_BASE_URL = 'https://netlicensing.labs64.com/core/v2/rest';

    private $curl = null;

    public $apiKey;

    /**
     * Initializes a NetLicensing object
     **/
    function __construct($apiKey)
    {
        $this->apiKey = $apiKey;

        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'NetLicensing/PHP ' . PHP_VERSION . ' (http://netlicensing.labs64.com)';

        $this->curl = new Curl();
        $this->curl->headers['Accept'] = 'application/json';
        $this->curl->user_agent = $user_agent;
    }

    /**
     * Validates active licenses of the licensee
     *
     * Returns a object containing licensee validation result
     *
     * @param string $productNumber
     * @param string $licenseeNumber
     * @param string $licenseeName
     * @return licensee validation result
     **/
    function validate($productNumber, $licenseeNumber, $licenseeName = '')
    {
        if (empty($licenseeName)) {
            $licenseeName = $licenseeNumber;
        }
        $url = self::NLIC_BASE_URL . '/licensee/' . $licenseeNumber . '/validate?productNumber=' . $productNumber . '&name=' . $licenseeName;
        if (!empty($this->apiKey)) {
            $url .= '&apiKey=' . $this->apiKey;
        }

        $response = $this->curl->get($url, $vars = array());

        return $response->body;
    }

}
