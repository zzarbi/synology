<?php

class Synology_Abstract
{

    const PROTOCOL_HTTP = 'http';

    const PROTOCOL_HTTPS = 'https';

    const API_NAMESPACE = 'SYNO';

    const CONNECT_TIMEOUT = 30000; // 30s
    private $_protocol = self::PROTOCOL_HTTP;

    private $_port = 80;

    private $_address = '';

    private $_version = 1;

    private $_serviceName = null;

    private $_namespace = null;

    private $_debug = false;
    
    private $_verifySSL = false;

    private $_errorCodes = array(
        100 => 'Unknown error',
        101 => 'No parameter of API, method or version',
        102 => 'The requested API does not exist',
        103 => 'The requested method does not exist',
        104 => 'The requested version does not support the functionality',
        105 => 'The logged in session does not have permission',
        106 => 'Session timeout',
        107 => 'Session interrupted by duplicate login'
    );

    /**
     * Setup API
     *
     * @param string $serviceName
     * @param string $namespace
     * @param string $address
     * @param int $port
     * @param string $protocol
     * @param int $version
     * @param boolean $verifySSL
     */
    public function __construct($serviceName, $namespace, $address, $port = null, $protocol = self::PROTOCOL_HTTP, $version = 1, $verifySSL = false)
    {
        $this->_serviceName = $serviceName;
        $this->_namespace = $namespace;
        $this->_address = $address;
        $this->_verifySSL = $verifySSL;
        if (! empty($port) && is_numeric($port)) {
            $this->_port = (int) $port;
        }
        
        if (! empty($protocol)) {
            $this->_protocol = $protocol;
        }
        
        $this->_version = $version;
    }

    /**
     * Get the base URL
     *
     * @return string
     */
    protected function _getBaseUrl()
    {
        return $this->_protocol . '://' . $this->_address . ':' . $this->_port . '/webapi/';
    }

    /**
     * Get ApiName
     * 
     * @param string $api
     */
    private function _getApiName($api)
    {
        return $this->_namespace . '.' . $this->_serviceName . '.' . $api;
    }

    /**
     * Process a request
     *
     * @param string $api
     * @param string $path
     * @param string $method
     * @param array $params
     * @param int $version
     * @param string $httpMethod
     * @return stdClass array bool
     */
    protected function _request($api, $path, $method, $params = array(), $version = null, $httpMethod = 'get')
    {
        if (! is_array($params)) {
            if (! empty($params)) {
                $params = array(
                    $params
                );
            } else {
                $params = array();
            }
        }
        $params['api'] = $this->_getApiName($api);
        $params['version'] = ((int) $version > 0) ? (int) $version : $this->_version;
        $params['method'] = $method;
        
        // create a new cURL resource
        $ch = curl_init();
        
        if ($httpMethod !== 'post') {
            $url = $this->_getBaseUrl() . $path . '?' . http_build_query($params);
            $this->log($url, 'Requested Url');
            
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            $url = $this->_getBaseUrl() . $path;
            $this->log($url, 'Requested Url');
            $this->log($params, 'Post Variable');
            
            // set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }
        
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, self::CONNECT_TIMEOUT);
        
        // Verify SSL or not
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->_verifySSL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->_verifySSL);
        
        // grab URL and pass it to the browser
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        
        $this->log($info['http_code'], 'Response code');
        if (200 == $info['http_code']) {
            if (preg_match('#(plain|text)#', $info['content_type'])) {
                return $this->_parseRequest($result);
            } else {
                return $result;
            }
        } else {
            curl_close($ch);
            if ($info['total_time'] >= (self::CONNECT_TIMEOUT / 1000)) {
                throw new Synology_Exception('Connection Timeout');
            } else {
                $this->log($result, 'Result');
                throw new Synology_Exception('Connection Error');
            }
        }
        
        // close cURL resource, and free up system resources
        curl_close($ch);
    }

    /**
     *
     * @param string $data
     * @throws Exception
     * @return stdClass array bool
     */
    private function _parseRequest($json)
    {
        if (($data = json_decode(trim($json))) !== null) {
            if ($data->success == 1) {
                if (isset($data->data)) {
                    return $data->data;
                } else {
                    return true;
                }
            } else {
                if (array_key_exists($data->error->code, $this->_errorCodes)) {
                    throw new Synology_Exception($this->_errorCodes[$data->error->code]);
                }
            }
        } else {
            // return raw data
            return $json;
        }
    }

    /**
     * Activate the debug mode
     *
     * @return Synology_Abstract
     */
    public function activateDebug()
    {
        $this->_debug = true;
        return $this;
    }

    /**
     * Log different data
     *
     * @param mixed $value
     * @param string $key
     */
    protected function log($value, $key = null)
    {
        if ($this->_debug) {
            if ($key != null) {
                echo $key . ': ';
            }
            if (is_object($value) || is_array($value)) {
                echo PHP_EOL . print_r($value, true);
            } else {
                echo $value;
            }
            echo PHP_EOL;
        }
    }
}