<?php

require_once "../loading.php";

// begin PHP session
session_start();

// output buffering
ob_start();

// namespaces
use Klein\Klein;
use Facebook\FacebookSession;

try{
	// initialize Facebook
	$fb_app_id = FacebookUtilities::GetFacebookAppId();
	$fb_app_secret = FacebookUtilities::GetFacebookAppSecret();
	FacebookSession::setDefaultApplication($fb_app_id, $fb_app_secret);

	// URL router
	$kleinRouter = new Klein();
	
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
} catch (Exception $e){
	exit();
}