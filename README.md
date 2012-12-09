Synology
=================

This is an simple implementation of the Synology API in PHP.
API Implemented so far:
- SYNO.Info
-- getAvailableApi
-- connect
-- disconnect

- SYNO.DownloadStation
-- connect
-- disconnect
-- getInfo
-- getConfig
-- setConfig
-- getScheduleConfig
-- setScheduleConfig
-- getTaskList
-- getTaskInfo
-- addTask
-- deleteTask
-- pauseTask
-- resumeTask
-- getStatistics
-- getRssList
-- refreshRss
-- getRssFeedList

Usage:
```php
$synology = new Synology_DownloadStation_Api('192.168.10.5', 5000, 'http', 1);
//$synology->activateDebug();
$synology->connect('admin', 'xxxx');
print_r($synology->getStatistics());
```