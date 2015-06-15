<?php

class Synology_AudioStation_Api extends Synology_Api_Authenticate
{

    const API_SERVICE_NAME = 'AudioStation';

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
     * Return Information about AudioStation
     * - is_manager
     * - version
     * - version_string
     */
    public function getInfo()
    {
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
    public function getObjects($type, $limit = 25, $offset = 0, $additional = 'song_tag,song_audio,song_rating')
    {
        $path = '';
        switch ($type) {
            case 'Album':
                $path = 'AudioStation/album.cgi';
                break;
            case 'Composer':
                $path = 'AudioStation/composer.cgi';
                break;
            case 'Genre':
                $path = 'AudioStation/genre.cgi';
                break;
            case 'Artist':
                $path = 'AudioStation/artist.cgi';
                break;
            case 'Folder':
                $path = 'AudioStation/folder.cgi';
                break;
            case 'Song':
                $path = 'AudioStation/song.cgi';
                break;
            case 'Radio':
                $path = 'AudioStation/radio.cgi';
                break;
            case 'Playlist':
                $path = 'AudioStation/playlist.cgi';
                break;
            case 'RemotePlayer':
                $path = 'AudioStation/remote_player.cgi';
                break;
            case 'MediaServer':
                $path = 'AudioStation/media_server.cgi';
                break;
            default:
                throw new Synology_Exception('Unknow "' . $type . '" object');
        }
        return $this->_request($type, $path, 'list', array(
            'limit' => $limit,
            'offset' => $offset,
            'additional' => $additional
        ));
    }

    /**
     * Get info about an object
     *
     * @param string $type (Folder|Song|Playlist)
     * @param strng $id
     * @return array
     */
    public function getObjectInfo($type, $id)
    {
        $path = '';
        switch ($type) {
            case 'Folder':
                $path = 'AudioStation/folder.cgi';
                break;
            case 'Song':
                $path = 'AudioStation/song.cgi';
                break;
            case 'Playlist':
                $path = 'AudioStation/playlist.cgi';
                break;
            default:
                throw new Synology_Exception('Unknow "' . $type . '" object');
        }
        return $this->_request($type, $path, 'getinfo', array(
            'id' => $id
        ));
    }

    /**
     * Get cover of an object
     *
     * @param string $type (Song|Folder)
     * @param strng $id
     * @return array
     */
    public function getObjectCover($type, $id)
    {
        $method = '';
        switch ($type) {
            case 'Song':
                $method = 'getsongcover';
                break;
            case 'Folder':
                $method = 'getfoldercover';
                break;
            default:
                throw new Synology_Exception('Unknow "' . $type . '" object');
        }
        return $this->_request('Cover', 'AudioStation/cover.cgi', $method, array(
            'id' => $id
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
    public function searchSong($name, $limit = 25, $offset = 0, $sortby = 'title', $sortdirection = 'asc')
    {
        return $this->_request('Song', 'AudioStation/song.cgi', 'search', array(
            'title' => $name,
            'limit' => $limit,
            'offset' => $offset,
            'sort_by' => $sortby,
            'sort_direction' => $sortdirection
        ));
    }

    /**
     * List Albums of an Artist
     *
     * @param string $artist
     * @param number $limit
     * @param string $sortby (name, ...)
     * @param string $sortdirection (asc|desc)
     * @return array
     */
    public function listAlbumsOfArtist($artist, $limit = -1, $sortby = 'name', $sortdirection = 'ASC')
    {
        return $this->_request('Album', 'AudioStation/album.cgi', 'list', array(
            'artist' => $artist,
            'limit' => $limit,
            'sort_by' => $sortby,
            'sort_direction' => $sortdirection
        ), 2, 'post');
    }

    /**
     * List Song objects in an Album
     *
     * @param string $artist
     * @param string $album
     * @param number $limit
     * @param string $sortby (track, ...)
     * @param string $sortdirection (asc|desc)
     * @param string $additional (song_tag, song_audio, song_rating)
     * @return array
     */
    public function listSongsInAlbum($artist, $album, $limit = -1, $sortby = 'track', $sortdirection = 'ASC', $additional = 'song_tag,song_audio,song_rating')
    {
        return $this->_request('Song', 'AudioStation/song.cgi', 'search', array(
            'album' => $album,
            'album_artist' => $artist,
            'limit' => $limit,
            'sort_by' => $sortby,
            'sort_direction' => $sortdirection,
            'additional' => $additional
        ), 2, 'post');
    }

    public function stream($id)
    {
        return $this->_request('Stream', 'AudioStation/stream.cgi', 'stream', array(
            'id' => $id
        ), 2, 'get');
    }
}