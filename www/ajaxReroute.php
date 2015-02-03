<?php

require_once "../loading.php";

// namespaces
use Klein\Klein;
use Facebook\FacebookSession;

try{
	// initialize Facebook
	$fb_app_id = Utilities::GetFacebookAppId();
	$fb_app_secret = Utilities::GetFacebookAppSecret();
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