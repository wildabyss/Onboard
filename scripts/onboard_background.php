<?php 

require_once "../loading.php";

$updater = new DiscussionPersistentUpdater();
$updater->updateDiscussions();