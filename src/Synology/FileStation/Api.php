<?php 
// see http://ukdl.synology.com/download/Document/DeveloperGuide/Synology_File_Station_API_Guide.pdf
class Synology_FileStation_Api extends Synology_Api_Authenticate
{

    const API_SERVICE_NAME = 'FileStation';

    const API_NAMESPACE = 'SYNO';

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
     * Return Information about FileStation
     * - is_manager
     * - version
     * - version_string
     */
    public function getInfo()
    {
        return $this->_request('Info', 'entry.cgi', 'get', [], 2);
    }

    /**
     * Get Available Shares
     *
     * @param string $onlywritable
     * @param number $limit
     * @param number $offset
     * @param string $sortby
     * @param string $sortdirection
     * @param bool $additional
     * @return array
     */
    public function getShares($onlywritable = false, $limit = 25, $offset = 0, $sortby = 'name', $sortdirection = 'asc', $additional = false)
    {
        return $this->_request('List', 'entry.cgi', 'list_share', array(
            'onlywritable' => $onlywritable,
            'limit' => $limit,
            'offset' => $offset,
            'sort_by' => $sortby,
            'sort_direction' => $sortdirection,
            'additional' => $additional ? 'real_path,owner,time,perm,volume_status' : ''
        ), 2);
    }

    /**
     * Get info about an object
     *
     * @param string $type (List|Sharing)
     * @param strng $id
     * @return array
     */
    public function getObjectInfo($type, $id)
    {
        $path = '';
        switch ($type) {
            case 'List':
                $path = 'FileStation/file_share.cgi';
                break;
            case 'Sharing':
                $path = 'FileStation/file_sharing.cgi';
                break;
            default:
                throw new Synology_Exception('Unknow "' . $type . '" object');
        }
        return $this->_request($type, $path, 'getinfo', array(
            'id' => $id
        ));
    }

    /**
     * Get a list of files/directories in a given path
     *
     * @param string $path like '/home'
     * @param number $limit
     * @param number $offset
     * @param string $sortby (name|size|user|group|mtime|atime|ctime|crtime|posix|type)
     * @param string $sortdirection
     * @param string $pattern
     * @param string $filetype (all|file|dir)
     * @param bool $additional
     * @return array
     */
    public function getList($path = '/home', $limit = 25, $offset = 0, $sortby = 'name', $sortdirection = 'asc', $pattern = '', $filetype = 'all', $additional = false)
    {
        return $this->_request('List', 'entry.cgi', 'list_share', array(
            'folder_path' => $path,
            'limit' => $limit,
            'offset' => $offset,
            'sort_by' => $sortby,
            'sort_direction' => $sortdirection,
            'pattern' => $pattern,
            'filetype' => $filetype,
            'additional' => $additional ? 'real_path,size,owner,time,perm' : ''
        ), 2);
    }

    /**
     * Search for files/directories in a given path
     *
     * @param string $pattern
     * @param string $path like '/home'
     * @param number $limit
     * @param number $offset
     * @param string $sortby (name|size|user|group|mtime|atime|ctime|crtime|posix|type)
     * @param string $sortdirection (asc|desc)
     * @param string $filetype (all|file|dir)
     * @param bool $additional
     * @return array
     */
    public function search($pattern, $path = '/home', $limit = 25, $offset = 0, $sortby = 'name', $sortdirection = 'asc', $filetype = 'all', $additional = false)
    {
        return $this->_request('List', 'entry.cgi', 'list', array(
            'folder_path' => $path,
            'limit' => $limit,
            'offset' => $offset,
            'sort_by' => $sortby,
            'sort_direction' => $sortdirection,
            'pattern' => $pattern,
            'filetype' => $filetype,
            'additional' => $additional ? 'real_path,size,owner,time,perm' : ''
        ), 2);
    }

    /**
     * Download a file
     *
     * @param string $path (comma separated)
     * @param string $mode
     * @return array
     */
    public function download($path, $mode = 'open')
    {
        return $this->_request('Download', 'entry.cgi', 'download', array(
            'path' => $path,
            'mode' => $mode
        ), 2);
    }

    /**
     * Create a folder
     * 
     * @param $path
     * @param $name
     * @param false $force_parent
     * @return bool|stdClass
     * @throws Synology_Exception
     */
    public function createFolder($path, $name, $force_parent = false)
    {
        return $this->_request('CreateFolder', 'entry.cgi', 'create', array(
            'folder_path' => $path,
            'name' => $name,
            'force_parent' => true,
        ), 2);
    }
}
