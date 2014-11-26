<?php 

	use Klein\Klein;
	
	require_once "../loading.php";
	
	// URL router
	$kleinRouter = new Klein();
	
	// check for login token
	if (!isset($logged_in)) {
		// redirect to home
		$kleinRouter->respond(array('GET','POST'), '/home', function () {
			include "../modules/home.php";
			return;
		});
		
		// redirect to community
		$kleinRouter->respond(array('GET','POST'), '/community', function () {
			include "../modules/community.php";
			return;
		});
		
		// redirect to login
		$kleinRouter->respond(array('GET','POST'), '/login', function () {
			include "../modules/login.php";
			return;
		});
		
		// redirect to browse
		$kleinRouter->respond(array('GET','POST'), '/browse', function () {
			include "../modules/browse.php";
			return;
		});
		
		// default to home
		$kleinRouter->onHttpError(function () {
			include "../modules/home.php";
			return;
		});
		
	} else {
		// redirect to login
		$kleinRouter->respond(function ($request) {
			include "../modules/login.php";
			return;
		});
	}
	
	$kleinRouter->dispatch();

?>