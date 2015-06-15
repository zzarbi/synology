<?php

class Synology_VideoStation_Api extends Synology_Api_Authenticate
{

    const API_SERVICE_NAME = 'VideoStation';

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
     * Return Information about VideoStation
     * - is_manager
     * - version
     * - version_string
     */
    public function getInfo()
    {
        return $this->_request('Info', 'VideoStation/info.cgi', 'getinfo');
    }

    /**
     * Get a list of objects
     *
     * @param string $type (Movie|TVShow|TVShowEpisode|HomeVideo|TVRecording|Collection|Library)
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getObjects($type, $limit = 25, $offset = 0)
    {
        $path = '';
        $type = ucfirst($type);
        switch ($type) {
            case 'Movie':
                $path = 'VideoStation/movie.cgi';
                break;
            case 'TVShow':
                $path = 'VideoStation/tvshow.cgi';
                break;
            case 'TVShowEpisode':
                $path = 'VideoStation/tvshow_episode.cgi';
                break;
            case 'HomeVideo':
                $path = 'VideoStation/homevideo.cgi';
                break;
            case 'TVRecording':
                $path = 'VideoStation/tvrecord.cgi';
                break;
            case 'Collection':
                $path = 'VideoStation/collection.cgi';
                break;
            case 'Library':
                $path = 'VideoStation/library.cgi';
                break;
            default:
                throw new Synology_Exception('Unknow "' . $type . '" object');
        }
        return $this->_request($type, $path, 'list', array(
            'limit' => $limit,
            'offset' => $offset
        ));
    }

    /**
     * Search for Movie|TVShow|TVShowEpisode|HomeVideo|TVRecording|Collection
     *
     * @param string $name
     * @param string $type (Movie|TVShow|TVShowEpisode|HomeVideo|TVRecording|Collection)
     * @param number $limit
     * @param number $offset
     * @param string $sortby (title|original_available)
     * @param string $sortdirection (asc|desc)
     * @return array
     */
    public function searchObject($name, $type, $limit = 25, $offset = 0, $sortby = 'title', $sortdirection = 'asc')
    {
        $path = '';
        $type = ucfirst($type);
        switch ($type) {
            case 'Movie':
                $path = 'VideoStation/movie.cgi';
                break;
            case 'TVShow':
                $path = 'VideoStation/tvshow.cgi';
                break;
            case 'TVShowEpisode':
                $path = 'VideoStation/tvshow_episode.cgi';
                break;
            case 'HomeVideo':
                $path = 'VideoStation/homevideo.cgi';
                break;
            case 'TVRecording':
                $path = 'VideoStation/tvrecord.cgi';
                break;
            case 'Collection':
                $path = 'VideoStation/collection.cgi';
                break;
            default:
                throw new Synology_Exception('Unknow "' . $type . '" object');
        }
        
        return $this->_request($type, $path, 'search', array(
            'title' => $name,
            'limit' => $limit,
            'offset' => $offset,
            'sort_by' => $sortby,
            'sort_direction' => $sortdirection
        ));
    }
}