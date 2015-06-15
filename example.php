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

$synology = new Synology_AudioStation_Api('192.168.10.5', 5001, 'https', 1);
$synology->activateDebug();
$synology->connect('admin', '****');
print_r($synology->getInfo());