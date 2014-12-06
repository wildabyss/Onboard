<?php

	use Klein\Klein;
	
	require_once "../loading.php";
	
	// begin PHP session
	session_start();
	
	// URL router
	$kleinRouter = new Klein();
	
	// FIXME time zone should vary with user location
	date_default_timezone_set('America/Toronto');
	
	if (isset($_SESSION['current_user'])) {
		// redirect to home
		$kleinRouter->respond('POST', '/ajaxActivityAssociation', function () {
			include "../modules/ajax/ajaxActivityAssociation.php";
			return;
		});
	
		// error response
		$kleinRouter->onHttpError(function ($code, $router) {
			return;
		});

	}
	
	$kleinRouter->dispatch();

?>