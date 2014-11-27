<?php

	use Klein\Klein;
	
	require_once "../loading.php";
	
	// URL router
	$kleinRouter = new Klein();
	
	// FIXME check for login token
	if (!isset($logged_in)) {
		// redirect to home
		$kleinRouter->respond('POST', '/ajaxLike', function () {
			include "../modules/ajax/ajaxLike.php";
			return;
		});
	
		// error response
		$kleinRouter->onHttpError(function ($code, $router) {
			return;
		});

	}
	
	$kleinRouter->dispatch();

?>