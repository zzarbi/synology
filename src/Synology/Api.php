<?php

class Synology_Api extends Synology_Abstract
{

    const API_SERVICE_NAME = 'API';

    const API_NAMESPACE = 'SYNO';

    private $_sid = null;

    private $_sessionName = 'default';

    /**
     * Info API setup
     *
     * @param string $address
     * @param int $port
     * @param string $protocol
     * @param int $version
     * @param boolean $verifySSL
     */
    public function __construct($address, $port = null, $protocol = null, $version = 1, $verifySSL = false)
    {
        parent::__construct(self::API_SERVICE_NAME, self::API_NAMESPACE, $address, $port, $protocol, $version, $verifySSL);
    }

    /**
     * Get a list of Service and Apis
     * 
     * @return array
     */
    public function getAvailableApi()
    {
        return $this->_request('Info', 'query.cgi', 'query', array('query' => 'all'));
    }

    /**
     * Connect to Synology
     *
     * @param string $username
     * @param string $password
     * @param string $sessionName
     * @return Synology_Api
     */
    public function connect($username, $password, $sessionName = null)
    {
        if (! empty($sessionName)) {
            $this->_sessionName = $sessionName;
        }
        
        $this->log($this->_sessionName, 'Connect Session');
        $this->log($username, 'User');
        
        $options = array(
            'account' => $username,
            'passwd' => $password,
            'session' => $this->_sessionName,
            'format' => 'sid'
        );
        $data = $this->_request('Auth', 'auth.cgi', 'login', $options, 2);
        
        // save session name id
        $this->_sid = $data->sid;
        
        return $this;
    }

    /**
     * Logout from Synology
     *
     * @return Synology_Api
     */
    public function disconnect()
    {
        $this->log($this->_sessionName, 'Disconnect Session');
        $this->_request('Auth', 'auth.cgi', 'logout', array(
            '_sid' => $this->_sid,
            'session' => $this->_sessionName
        ));
        $this->_sid = null;
        return $this;
    }

    /**
     * Return Session Id
     *
     * @throws Synology_Exception
     * @return string
     */
    public function getSessionId()
    {
        if ($this->_sid) {
            return $this->_sid;
        } else {
            throw new Synology_Exception('Missing session');
        }
    }

    /**
     * Return true if connected
     *
     * @return boolean
     */
    public function isConnected()
    {
        if (! empty($this->_sid)) {
            return true;
        }
        return false;
    }

    /**
     * Return Session Name
     *
     * @return string
     */
    public function getSessionName()
    {
        return $this->_sessionName;
    }

    public function __destruct()
    {
        if ($this->_sid !== null) {
            $this->disconnect();
        }
    }
}
