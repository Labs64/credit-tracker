<?php

/**
 * A basic NetLicensing PHP client
 *
 * @package netlicensing
 * @author Labs64 <netlicensing@labs64.com>
 **/
if (!class_exists('NetLicensing')) {

    class NetLicensing
    {

        const NLIC_BASE_URL = 'https://netlicensing.labs64.com/core/v2/rest';

        private $curl = null;

        /**
         * Initializes a NetLicensing object
         **/
        function __construct($apiKey)
        {
            $user_agent = 'NetLicensing/PHP ' . PHP_VERSION . ' (http://netlicensing.io)' . '; ' . $_SERVER['HTTP_USER_AGENT'];

            $this->curl = new Curl();
            $this->curl->headers['Authorization'] = 'Basic ' . base64_encode("apiKey:" . $apiKey);
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
            $params = array(
                'productNumber' => $productNumber,
                'licenseeName' => $licenseeName,
            );
            $url = self::NLIC_BASE_URL . '/licensee/' . $licenseeNumber . '/validate';

            $response = $this->curl->get($url, $params);

            return $response->body;
        }

    }

}
