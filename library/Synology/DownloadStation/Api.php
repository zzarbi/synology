<?php
class Synology_DownloadStation_Api extends Synology_Abstract{
	const API_NAME = 'SYNO.DownloadStation';
	const SESSION_NAME = 'DownloadStation';
	
	private $_authApi = null;
	
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
		$this->_authApi = new Synology_Api($address, $port, $protocol, $version);
	}
	
	/**
	 * Connect to Synology
	 * 
	 * @param string $login
	 * @param string $password
	 */
	public function connect($login, $password){
		return $this->_authApi->connect($login, $password, self::SESSION_NAME);
	}
	
	/**
	 * Return Information about synology
	 * - is_manager
	 * - version
	 * - version_string
	 */
	public function getInfo(){
		return $this->_request('Info', 'DownloadStation/info.cgi', 'getinfo');
	}
	
	/**
	 * Get Limitation settings
	 * 
	 * @return boolean
	 */
	public function getConfig(){
		return $this->_request('Info', 'DownloadStation/info.cgi', 'getconfig');
	}
	
	/**
	 * Get a list of Task
	 * 
	 * @param int $offset
	 * @param int $limit
	 * @param array $additional (detail,transfer,file,tracker,peer)
	 * @return boolean
	 */
	public function getTaskList($offset = 0, $limit = -1, $additional = null){
		$params = array();
		if(isset($offset)){
			$params['offset'] = $offset;
		}
		
		if(isset($limit) && $limit > 0){
			$params['limit'] = $limit;
		}
		
		if(isset($additional) && is_array($additional)){
			$params['additional'] = implode(',', $additional);
		}
		return $this->_request('Task', 'DownloadStation/task.cgi', 'list', $params);
	}
	
	/**
	 * Get more info about a task
	 * 
	 * @param string|array $taskId (one ID or a list of ID)
	 * @param array $additional (detail,transfer,file,tracker,peer)
	 * @return boolean
	 */
	public function getTaskInfo($taskId, $additional = null){
		$params = array();
		
		if(is_array($taskId)){
			$params['id'] = implode(',', $taskId);
		}else{
			$params['id'] = $taskId;
		}
		
		if(isset($additional) && is_array($additional)){
			$params['additional'] = implode(',', $additional);
		}
		return $this->_request('Task', 'DownloadStation/task.cgi', 'getinfo', $params);
	}
	
	/* (non-PHPdoc)
	 * @see Synology_Abstract::_request()
	 */
	protected function _request($api, $path, $method, $params = array(), $version = null){
		if($this->_authApi->isConnected()){
			if(!is_array($params)){
				if(!empty($params)){
					$params = array($params);
				}else{
					$params = array();
				}
			}
			
			$params['_sid'] = $this->_authApi->getSessionId();
			
			return parent::_request($api, $path, $method, $params, $version);
		}
		throw new Synology_Exception('Not Connected');
	}
	
	/* (non-PHPdoc)
	 * @see Synology_Abstract::activateDebug()
	 */
	public function activateDebug(){
		parent::activateDebug();
		$this->_authApi->activateDebug();
	}
}