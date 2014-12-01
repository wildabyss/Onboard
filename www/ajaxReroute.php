<?php

	use Klein\Klein;
	
	require_once "../loading.php";
	
	// URL router
	$kleinRouter = new Klein();
	
	// FIXME time zone should vary with user location
	date_default_timezone_set('America/Toronto');
	
	// FIXME check for login token
	// FIXME: For development testing only
	$_CUR_USER = UserQuery::create()
		->filterByDisplayName('Kiah Bransch')
		->findOne();
	
	if (isset($_CUR_USER)) {
		// redirect to home
		$kleinRouter->respond('POST', '/ajaxActivityAssociation', function () use ($_CUR_USER) {
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