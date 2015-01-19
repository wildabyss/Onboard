<?php

require_once "../loading.php";

// PHP error reporting
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

// begin PHP session
session_start();

// output buffering
ob_start();

use Klein\Klein;

// URL router
$kleinRouter = new Klein();

// FIXME time zone should vary with user location
date_default_timezone_set('America/Toronto');

if (isset($_SESSION['current_user'])) {
	$kleinRouter->respond('POST', '/ajaxActivityAssociation', function () {
		include "../modules/ajax/ajaxActivityAssociation.php";
		return;
	});
	
	$kleinRouter->respond('POST', '/ajaxDiscussion', function () {
		include "../modules/ajax/ajaxDiscussion.php";
		return;
	});

	// error response
	$kleinRouter->onHttpError(function ($code, $router) {
		return;
	});

}

$kleinRouter->dispatch();