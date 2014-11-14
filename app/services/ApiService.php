<?php
/*
 * RESTful API service
 */

use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\File;

Class ApiService {

    protected $_cache;
    protected $_client;
    public $config;

    public function __construct() {
        $this->config = Util::config('app');

        // cache
        $adapter = new File($this->config['cache.path']);
        $adapter->setOption('ttl', $this->config['cache.time']);
        $this->_cache = new Cache($adapter);

        // client
        $this->_client = new HttpClient();
        $this->_client->setCookie(1, $this->config['cookie.file']);
        $this->_client->setHeader(array('Accept-Language: sv'));
    }

    protected function _requestJson($url) {
        $response = $this->_client->get($this->config['api.site'].$url);
        $json = json_decode($response, true);
        return $json;
    }

    /**
     * Request /api/devices
     * @return string
     * @throws Exception
     */
    public function device() {
        $url = "/Neonstingray.Nettv4.RestApi/api/devices?apiKey=8c1611af2f394cc391d2d81e0ed4b730&manufacturer=Fake&udid=Fake&model=Fake";
        $json = $this->_requestJson($url);
        if (empty($json)) {
            throw new Exception("Invalid JSON returned by " . __METHOD__);
        }
        $template = $json[0]['Home']['Href'];
        $result = str_replace('{language}', 'sv', $template);
        return $result;
    }

    /**
     * Request /api/pages
     * @return string
     * @throws Exception
     */
    public function page() {
        $url = $this->device();
        $json = $this->_requestJson($url);
        if (empty($json)) {
            throw new Exception("Invalid JSON returned by " . __METHOD__);
        }
        $result = $json[0]['Content']['Teasers'][0]['Medias']['Href'];
        return $result;
    }

    /**
     * Get media data
     * @return array
     * @throws Exception
     */
    public function media() {
        $result = $this->_cache->get(__METHOD__);
        if (empty($result)) {
            $url = $this->page();
            $json = $this->_requestJson($url);
            if (empty($json)) {
                throw new Exception("Invalid JSON returned by ".__METHOD__);
            }
            $result = $json;
            $this->_cache->set(__METHOD__, $result);
        }
        return $result;
    }

    public function getMediaList() {
        $tmp = $this->media();
        return $tmp['Items'];
    }

}
