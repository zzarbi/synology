<?php
class Synology_DTV_Api extends Synology_Abstract{
	const API_NAME = 'SYNO.DTV';
	
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
}