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
		return $this->_request('Info', 'AudioStation/info.cgi', 'getinfo', array(), 2);
	}
	
	/**
	 * Get a list of objects
	 * 
	 * @param string $type (Album|Composer|Genre|Artist|Folder|Song|Radio|Playlist|RemotePlayer|MediaServer)
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function getObjects($type, $limit = 25, $offset = 0){
		$path = '';
		switch ($type){
			case 'Album':
				$path = 'AudioStation/album.cgi';break;
			case 'Composer':
				$path = 'AudioStation/composer.cgi';break;
			case 'Genre':
				$path = 'AudioStation/genre.cgi';break;
			case 'Artist':
				$path = 'AudioStation/artist.cgi';break;
			case 'Folder':
				$path = 'AudioStation/folder.cgi';break;
			case 'Song':
				$path = 'AudioStation/song.cgi';break;
			case 'Radio':
				$path = 'AudioStation/radio.cgi';break;
			case 'Playlist':
				$path = 'AudioStation/playlist.cgi';break;
			case 'RemotePlayer':
				$path = 'AudioStation/remote_player.cgi';break;
			case 'MediaServer':
				$path = 'AudioStation/media_server.cgi';break;
			default:
				new Synology_Exception('Unknow "'.$type.'" object');
		}
		return $this->_request($type, $path, 'list', array('limit' => $limit, 'offset' => $offset));
	}
	
	/**
	 * Get info about an object
	 * 
	 * @param string $type (Folder|Song|Playlist)
	 * @param strng $id
	 * @return array
	 */
	public function getObjectInfo($type, $id){
		$path = '';
		switch ($type){
			case 'Folder':
				$path = 'AudioStation/folder.cgi';break;
			case 'Song':
				$path = 'AudioStation/song.cgi';break;
			case 'Playlist':
				$path = 'AudioStation/playlist.cgi';break;
			default:
				new Synology_Exception('Unknow "'.$type.'" object');
		}
		return $this->_request($type, $path, 'getinfo', array('id' => $id));
	}
	
	/**
	 * Get cover of an object
	 * 
	 * @param string $type (Song|Folder)
	 * @param strng $id
	 * @return array
	 */
	public function getObjectCover($type, $id){
		$method = '';
		switch ($type){
			case 'Song':
				$method = 'getsongcover';break;
			case 'Folder':
				$method = 'getfoldercover';break;
			default:
				new Synology_Exception('Unknow "'.$type.'" object');
		}
		return $this->_request('Cover', 'AudioStation/cover.cgi', $method, array('id' => $id));
	}
}