<?php

/**
 * Flickr parser
 *
 * @package parser
 * @author Labs64 <info@labs64.com>
 **/
class CTFlickr extends CTParser
{

    const COPYRIGHT = '&copy; %author% - Flickr.com';

    const BASE_URL = 'https://api.flickr.com/services/rest/';

    private $apiKey;

    function __construct($apiKey)
    {
        parent::__construct();

        $this->apiKey = $apiKey;
    }

    protected function parse($number)
    {
        $item = parent::parse($number);
        $item['source'] = 'Flickr';
        $item['publisher'] = 'Flickr';
        $item['license'] = '';

        $photosGetInfo_resp = $this->photosGetInfo($number);

        if ($photosGetInfo_resp['stat'] == 'ok') {
            $realname = $photosGetInfo_resp['photo']['owner']['realname'];
            $username = $photosGetInfo_resp['photo']['owner']['username'];
            $item['author'] = (empty($realname)) ? $username : $realname;

            // TODO: parse photo.urls.url
            $item['link'] = '';

            $license_id = $photosGetInfo_resp['photo']['license'];
            $photosLicensesGetInfo_resp = $this->photosLicensesGetInfo();
            $lic_array = $this->findLicensesById($license_id, $photosLicensesGetInfo_resp['licenses']['license']);

            if (empty($lic_array['url'])) {
                $item['license'] = $lic_array['name'];
            } else {
                $item['license'] = '<a href="' . $lic_array['url'] . '" target="__blank">' . $lic_array['name'] . '</a>';
            }

        } else {
            $item['info'] = $photosGetInfo_resp['code'] . ': ' . $photosGetInfo_resp['message'];
            // TODO: use only info block as soon as this implemented
            $item['author'] = $photosGetInfo_resp['code'] . ': ' . $photosGetInfo_resp['message'];
        }

        return $item;
    }

    private function photosGetInfo($number)
    {
        $params = array(
            'method' => 'flickr.photos.getInfo',
            'api_key' => $this->apiKey,
            'photo_id' => $number,
            'format' => 'php_serial',
        );
        $response = $this->curl->get(self::BASE_URL, $params);
        return unserialize($response->body);
    }

    private function photosLicensesGetInfo()
    {
        $params = array(
            'method' => 'flickr.photos.licenses.getInfo',
            'api_key' => $this->apiKey,
            'format' => 'php_serial',
        );
        $response = $this->curl->get(self::BASE_URL, $params);
        return unserialize($response->body);
    }

    private function findLicensesById($id, $licenses = array())
    {
        foreach ($licenses as $license) {
            if ($license['id'] == $id) {
                return $license;
            }
        }
    }

}
