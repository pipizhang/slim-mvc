<?php
/**
 * HttpClient
 */

if (!function_exists('curl_init')) {
    die('Please install curl extension first.');
}

class HttpClient {

    public $userAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)';
    public $timeOut = 15;
    public $maxRedirs = 0;
    public $referer = '';
    public $headers;
    public $cookie = false;
    public $cookisFile;
    public $compression;
    public $proxy;
    public $proxySize = 0;
    public $proxyIndex = 0;

    protected $_info = array();
    protected $_error = array();

    /* Determines if error must be shown */
    public $bShowErros = false;
    /* Determines if Exception must be thrown */
    public $bExceptions = true;

    /**
     * Constructor.
     */
    public function __construct() {
    }

    /**
     * Set UserAgent
     *
     * @param string $userAgent
     * @return void
     */
    public function setUserAgent($userAgent) {
        $this->userAgent = $userAgent;
    }

    /**
     * Set Timeout
     *
     * @param int $timeout
     */
    public function setTimeout($timeout) {
        $this->timeOut = $timeout;
    }

    /**
     * Proxy
     *
     * @param string $url
     * @return void
     */
    public function addProxyServer($url) {
        $this->proxy[] = $url;
        ++$this->proxySize;
    }

    /**
     * Use Cookie
     *
     * @param boolean $active
     * @param string $cookeFile
     * @return void
     */
    public function setCookie($active, $cookeFile) {
        $this->cookie = (bool)$active;
        if (!empty($cookeFile)) {
            if (!file_exists($cookeFile)) {
                @touch($cookeFile);
            }
            if (is_file($cookeFile)) {
                $this->cookisFile = realpath($cookeFile);
            }
        }
        if ($this->cookie && empty($this->cookisFile)) {
            throw new Exception('The cookie file could not be opened. Make sure this directory has the correct permissions');
        }
    }

    /**
     * Set max redir
     *
     * @param int $n
     * @return void
     */
    public function setMaxRedirs($n) {
        $this->maxRedirs = $n;
    }

    /**
     * Http referer
     *
     * @param string $referer
     * @return void
     */
    public function setReferer($referer) {
        $this->referer = $referer;
    }

    /**
     * Get error info
     *
     * @return array
     */
    public function getError() {
        return $this->_error;
    }

    /**
     * Get last request info
     *
     * @return array
     */
    public function getInfo() {
        return $this->_info;
    }

    public function setHeader($arr) {
        $this->headers = $arr;
    }

    /**
     * Init client
     *
     * @return void
     */
    protected function _initClient() {
        $this->_info = $this->_error = array();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $this->referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);

        if (is_array($this->headers) && sizeof($this->headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        }
        if ($this->timeOut > 0) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeOut);
        }
        if ($this->maxRedirs > 0) {
            curl_setopt($ch, CURLOPT_MAXREDIRS, $this->maxRedirs);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }
        if ($this->proxySize > 0) {
            $proxy = $this->proxy[$this->proxyIndex++ % $this->proxySize];
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
        if ($this->cookie) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookisFile);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookisFile);
        }
        if (in_array($this->compression, array('', 'identity', 'deflate', 'gzip'))) {
            curl_setopt($ch, CURLOPT_ENCODING, $this->compression);
        }

        return $ch;
    }

    /**
     * Set Request info
     *
     * @param resource $ch
     * @param string $error
     * @return void
     */
    protected function _setInfo($ch, $error=null) {
        $this->_info = curl_getinfo($ch);
        if (curl_errno($ch) && $this->_error['error']!='') {
            $this->_error = array(
                'errno' => curl_errno($ch),
                'error' => curl_error($ch),
            );
        }
        if ($error!=null) {
            $this->_error = array(
                'errno' => 1,
                'error' => $error,
            );
        }
    }

    /**
     * HTTP GET
     *
     * @param string $url
     * @return string - contents
     */
    public function get($url) {
        $ch = $this->_initClient();
        curl_setopt($ch, CURLOPT_URL, $url);
        if (preg_match('/^https/', $url)) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  FALSE);
        }
        $return = curl_exec($ch);
        $this->_setInfo($ch);
        curl_close($ch);
        return $return;
    }

    /**
     * HTTP POST
     *
     * @param string $url - a valid url resource
     * @param string $data - url encoded query string or array
     * @return string - contents
     */
    public function post($url, $data='') {
        $ch = $this->_initClient();

        if (is_array($data) && sizeof($data) > 0) {
            $postData = http_build_query($data);
        } else {
            $postData = $data;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        if (preg_match('/^https/', $url)) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  FALSE);
        }

        $return = curl_exec($ch);
        $this->_setInfo($ch);
        curl_close($ch);
        return $return;
    }

    /**
     * HEAD
     *
     * @param type $url
     * @return type
     */
    public function head($url) {
        $ch = $this->_initClient();
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        if (preg_match('/^https/', $url)) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  FALSE);
        }

        $return = curl_exec($ch);
        $this->_setInfo($ch);
        curl_close($ch);
        return $return;
    }

}

