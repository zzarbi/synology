<?php
class Synology_AudioStation_Api extends Synology_Api_Authenticate{
	const API_SERVICE_NAME = 'AudioStation';
	const API_NAMESPACE = 'SYNO';
	
	/**
	 * Info API setup
	 * 
	 * @param string $address
	 * @param int $port
	 * @param string $protocol
	 * @param int $version
	 */
	public function __construct($address, $port = null, $protocol = null, $version = 1){
		parent::__construct(self::API_SERVICE_NAME, self::API_NAMESPACE, $address, $port, $protocol, $version);
	}
	
	/**
	 * Return Information about AudioStation
	 * - is_manager
	 * - version
	 * - version_string
	 */
	public function getInfo(){
		return $this->_request('Info', 'AudioStation/info.cgi', 'getinfo');
	}
}