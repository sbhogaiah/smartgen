<?php
/*
 * Errors setting
*/
ini_set('display_errors', true);

/*
 * Time Zone
*/
date_default_timezone_set('Asia/Calcutta');

/*
 * Global Settings
*/
define('BASE_URL', 'http://testserver.utthanasystems.com/smartgen/');
define('AUTO_BASE_URL', 'http://testserver.utthanasystems.com/smartgen/modules/alogout.php');
define('DB_SERVER', 'localhost');
define('DB_NAME', 'smartgen');
define('DB_USER', 'Aarunya');
define('DB_PASS', 'soma1234');

/*
 * Helpers
*/
include_once 'app_classes.php';

/*
 * Functions
*/
// Transform hours like "1:45" into the total number of minutes, "105". 
function hoursToMinutes($hours) 
{ 
    $minutes = 0; 
    if (strpos($hours, ':') !== false) 
    { 
        // Split hours and minutes. 
        list($hours, $minutes) = explode(':', $hours); 
    } 
    return $hours * 60 + $minutes; 
} 

// Transform minutes like "105" into hours like "1:45". 
function minutesToHours($minutes) 
{ 
    $hours = (int)($minutes / 60); 
    $minutes -= $hours * 60; 
    return sprintf("%d:%02.0f", $hours, $minutes); 
}  