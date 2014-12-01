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
		// home page
		$kleinRouter->respond(array('GET','POST'), '/', function () use ($_CUR_USER) {
			include "../modules/my_activities.php";
			return;
		});
		
		// redirect to recent activities
		$kleinRouter->respond(array('GET','POST'), '/recent', function () use ($_CUR_USER) {
			include "../modules/recent.php";
			return;
		});
		
		// redirect to community
		$kleinRouter->respond(array('GET','POST'), '/community', function () use ($_CUR_USER) {
			include "../modules/community.php";
			return;
		});
		
		// redirect to login
		$kleinRouter->respond(array('GET','POST'), '/login', function () use ($_CUR_USER) {
			include "../modules/login.php";
			return;
		});
		
		// redirect to browse
		$kleinRouter->respond(array('GET','POST'), '/browse', function () use ($_CUR_USER) {
			include "../modules/browse.php";
			return;
		});
		
		// redirect to my activities
		$kleinRouter->respond(array('GET','POST'), '/mylist', function () use ($_CUR_USER) {
			include "../modules/my_activities.php";
			return;
		});
		
		// error response
		$kleinRouter->onHttpError(function ($code, $router) {
			$router->response()->body('Page not found.');
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