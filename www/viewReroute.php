<?php 

// PHP error reporting
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

use Klein\Klein;
use Facebook\FacebookSession;

require_once "../loading.php";

// begin PHP session
session_start();

/*try{*/
	// initialize Facebook
	$fb_app_id = Utilities::GetFacebookAppId();
	$fb_app_secret = Utilities::GetFacebookAppSecret();
	FacebookSession::setDefaultApplication($fb_app_id, $fb_app_secret);

	// URL router
	$kleinRouter = new Klein();
	
	// FIXME time zone should vary with user location
	date_default_timezone_set('America/Toronto');
	
	if (isset($_SESSION['current_user'])) {
		// home page
		$kleinRouter->respond(array('GET','POST'), '/', function () {
			include "../modules/my_activities.php";
			return;
		});
		
		// redirect to recent activities
		$kleinRouter->respond(array('GET','POST'), '/recent', function () {
			include "../modules/recent.php";
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
		
		// redirect to login
		$kleinRouter->respond(array('GET','POST'), '/logout', function () {
			include "../modules/logout.php";
			return;
		});
		
		// redirect to browse
		$kleinRouter->respond(array('GET','POST'), '/browse', function () {
			include "../modules/browse.php";
			return;
		});
		
		// redirect to my activities
		$kleinRouter->respond(array('GET','POST'), '/mylist', function () {
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
	
/*} catch (PDOException $e){
	die("Database down");
} catch (FacebookRequestException $e){
	die("Unable to connect to Facebook");
} catch (Exception $e){
	die("Unexpected server error");
}*/