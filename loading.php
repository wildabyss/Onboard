<?php

// This script should be loaded once at the beginning of any front end execution

// PHP error reporting
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

// priority loading
require_once(__DIR__."/vendor/autoload.php");
require_once(__DIR__."/database/config.php");

// custom loading
require_once(__DIR__."/modules/classes/utilities.php");
require_once(__DIR__."/modules/classes/facebook_util.php");
require_once(__DIR__."/modules/classes/discussion_util.php");
require_once(__DIR__."/modules/classes/search.php");

// FIXME time zone should vary with user location
date_default_timezone_set('America/Toronto');