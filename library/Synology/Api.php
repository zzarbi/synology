<?php
class Synology_Api extends Synology_Abstract{
	const API_NAME = 'SYNO.API';
	
	/**
	 * Info API setup
	 * 
	 * @param string $address
	 * @param int $port
	 * @param string $protocol
	 * @param int $version
	 */
	public function __construct($address, $port = null, $protocol = null, $version = 1){
		parent::__construct(self::API_NAME, $address, $port, $protocol, $version);
	}
	
	/**
	 * Get a list of Service and Apis
	 * @return array
	 */
	public function getAvailableApi(){
		$services = array();
		foreach($this->_request('Info', 'query.cgi', 'query', array('query'=>'all')) as $key => $value){
			$keys = explode('.', $key);
			if(!array_key_exists($keys[0], $services)){
				$services[$keys[0]] = array();
			}
			
			
			if(!array_key_exists($keys[1], $services[$keys[0]])){
				$services[$keys[0]][$keys[1]] = array();
			}
			
			$services[$keys[0]][$keys[1]][$keys[2]] = $value;
		}
		print_r($services);
	}
}