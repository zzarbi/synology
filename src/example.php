<?php
/**
 * This file is an example on how to use Synology_Api
 */
set_include_path(dirname(__FILE__) . '/library' . PATH_SEPARATOR . get_include_path());

function __autoload($class_name)
{
    $path = str_replace('_', DIRECTORY_SEPARATOR, $class_name);
    include $path . '.php';
}

// Basic connectiont
$synology = new Synology_Api('192.168.10.5', 5001, 'https', 1);
$synology->activateDebug();
$synology->connect('admin', '****');
print_r($synology->getAvailableApi());


// Get a list of latest moive added
$synology = new Synology_VideoStation_Api('192.168.10.5', 5001, 'https', 1);
$synology->activateDebug();
$synology->connect('admin', '****');
print_r($synology->listObjects('Movie'));