<?php 

/* This script is to be ran at server startup */

require_once "loading.php";

// discussion updater
$discUpdater = new DiscussionPersistentUpdater();
