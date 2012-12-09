<?php
class Synology_Exception extends Exception{
	public function __construct($message = null, $code = null){
		if(empty($message)){
			switch($code){
				case 101:
					$message = 'Invalid parameter';
				case 102:
					$message = 'The requested API does not exist';
				case 103:
					$message = 'The requested method does not exist';
				case 104:
					$message = 'The requested version does not support the functionality';
				case 105:
					$message = 'The logged in session does not have permission';
				case 106:
					$message = 'Session timeout';
				case 107:
					$message = 'Session interrupted by duplicate login';
				default:
					$message = 'Unknown';
			}
		}
		parent::__construct($message, $code);
	}
}