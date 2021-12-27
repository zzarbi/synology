Synology PHP
=================

This is a PHP Library that consume Synology APIs

* SYNO.Api :
    * connect
    * disconnect
    * getAvailableApi

* SYNO.DownloadStation :
    * connect
    * disconnect
    * getInfo
    * getConfig
    * setConfig
    * getScheduleConfig
    * setScheduleConfig
    * getTaskList
    * getTaskInfo
    * addTask
    * deleteTask
    * pauseTask
    * resumeTask
    * getStatistics
    * getRssList
    * refreshRss
    * getRssFeedList

* SYNO.AudioStation:
    * connect
    * disconnect
    * getInfo
    * getObjects
    * getObjectInfo
    * getObjectCover
    * searchSong
    
* SYNO.FileStation:
    * connect
    * disconnect
    * getInfo
    * getShares
    * getObjectInfo
    * getList
    * search
    * download
    * createFolder
    
* SYNO.VideoStation:
    * connect
    * disconnect
    * getInfo
    * getObjects
    * searchObject
    * listObjects
    
Usage for Synology Api:
```php
$synology = new Synology_Api('192.168.10.5', 5000, 'http', 1);
//$synology->activateDebug();
$synology->connect('admin', 'xxxx');
print_r($synology->getAvailableApi());
``` 
 
Usage for AudioStation:
```php
$synology = new Synology_AudioStation_Api('192.168.10.5', 5000, 'http', 1);
$synology->connect('admin', 'xxxx');
print_r($synology->getInfo());
```
