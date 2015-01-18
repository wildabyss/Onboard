<?php

// This script should be loaded once at the beginning of any front end execution

// priority loading
require_once(__DIR__."/vendor/autoload.php");
require_once(__DIR__."/database/config.php");

// custom loading
require_once(__DIR__."/modules/utilities.php");
require_once(__DIR__."/modules/facebook_util.php");
require_once(__DIR__."/modules/discussion_util.php");

?>