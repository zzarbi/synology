<?php
class Synology_Abstract {
	const PROTOCOL_HTTP = 'http';
	const PROTOCOL_HTTPS = 'https';
	
	private $_protocol = self::PROTOCOL_HTTP;
	private $_port = 80;
	private $_address = '';
	private $_version = 1;
	private $_name = null;
	private $_debug= false;
	
	/**
	 * Setup API
	 * 
	 * @param string $name
	 * @param string $address
	 * @param int $port
	 * @param string $protocol
	 * @param int $version
	 */
	public function __construct($name, $address, $port = null, $protocol = self::PROTOCOL_HTTP, $version = 1){
		$this->_name = $name;
		$this->_address = $address;
		if(!empty($port) && is_numeric($port)){
			$this->_port = (int)$port;
		}
		
		if(!empty($protocol)){
			$this->_protocol = $protocol;
		}
		
		$this->_version = $version;
	}
	
	/**
	 * Get the base URL
	 * 
	 * @return string
	 */
	protected function _getBaseUrl(){
		return $this->_protocol.'://'.$this->_address.':'.$this->_port.'/webapi/';
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
	 * @return stdClass|array|bool
	 */
	protected function _request($api, $path, $method, $params = array(), $version = null, $httpMethod = 'get'){
		if(!is_array($params)){
			if(!empty($params)){
				$params = array($params);
			}else{
				$params = array();
			}
		}
		$params['api'] = $this->_name.'.'.$api;
		$params['version'] = ((int)$version>0)?(int)$version:$this->_version;
		$params['method'] = $method;
		
		// create a new cURL resource
		$ch = curl_init();
		
		if($httpMethod !== 'post'){
			$url = $this->_getBaseUrl().$path.'?'.http_build_query($params);
			$this->log($url, 'Requested Url');
			
			curl_setopt($ch, CURLOPT_URL, $url);
		}else{
			$url = $this->_getBaseUrl().$path;
			$this->log($url, 'Requested Url');
			$this->log($params, 'Post Variable');
			
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POST, count($params));
			curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($params));
		}
		
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		// grab URL and pass it to the browser
		$result = curl_exec($ch);
		$info = curl_getinfo($ch);
		
		$this->log($info['http_code'], 'Response code');
		if($info['http_code'] == 200){
			return $this->_parseRequest($result);
		}else{
			curl_close($ch);
			throw new Synology_Exception('Connection Error');
		}
		
		// close cURL resource, and free up system resources
		curl_close($ch);
	}
	
	/**
	 * @param string $data
	 * @throws Exception
	 * @return stdClass|array|bool
	 */
	private function _parseRequest($json){
		if(($data = json_decode(trim($json))) !== null){
			if($data->success == 1){
				if(isset($data->data)){
					return $data->data;
				}else{
					return true;
				}
			}else{
				throw new Synology_Exception(null, $data->error->code);
			}
		}else{
			throw new Synology_Exception();
		}
	}
	
	/**
	 * Activate the debug mode
	 */
	public function activateDebug(){
		$this->_debug = true;
	}
	
	/**
	 * Log different data
	 * 
	 * @param mixed $value
	 * @param string $key
	 */
	protected function log($value, $key = null){
		if($this->_debug){
			if($key != null){
				echo $key.': ';
			}
			if(is_object($value) || is_array($value)){
				echo PHP_EOL.print_r($value, true);
			}else{
				echo $value;
			}
			echo PHP_EOL;
		}
	}
}