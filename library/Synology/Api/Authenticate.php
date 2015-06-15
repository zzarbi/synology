<?php

class Synology_Api_Authenticate extends Synology_Abstract
{

    private $_authApi = null;

    private $_sessionName = null;

    /**
     * Cosntructor
     * 
     * @param string $serviceName
     * @param string $namespace
     * @param string $address
     * @param int $port
     * @param string $protocol
     * @param int $version
     */
    public function __construct($serviceName, $namespace, $address, $port = null, $protocol = null, $version = 1, $verifySSL = false)
    {
        parent::__construct($serviceName, $namespace, $address, $port, $protocol, $version, $verifySSL);
        $this->_sessionName = $serviceName;
        $this->_authApi = new Synology_Api($address, $port, $protocol, $version);
    }

    /**
     * Connect to Synology
     *
     * @param string $login
     * @param string $password
     */
    public function connect($login, $password)
    {
        return $this->_authApi->connect($login, $password, $this->_sessionName);
    }

    /**
     * Disconect to Synology
     */
    public function disconnect()
    {
        return $this->_authApi->disconnect();
    }
    
    /*
     * (non-PHPdoc) @see Synology_Abstract::_request()
     */
    protected function _request($api, $path, $method, $params = array(), $version = null, $httpMethod = 'get')
    {
        if ($this->_authApi->isConnected()) {
            if (! is_array($params)) {
                if (! empty($params)) {
                    $params = array(
                        $params
                    );
                } else {
                    $params = array();
                }
            }
            
            $params['_sid'] = $this->_authApi->getSessionId();
            
            return parent::_request($api, $path, $method, $params, $version, $httpMethod);
        }
        throw new Synology_Exception('Not Connected');
    }
    
    /*
     * (non-PHPdoc) @see Synology_Abstract::activateDebug()
     */
    public function activateDebug()
    {
        parent::activateDebug();
        $this->_authApi->activateDebug();
    }
}