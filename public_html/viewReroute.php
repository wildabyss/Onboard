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

	// check to make sure the user has logged in
	if (isset($_SESSION['current_user'])) {
		$curUser = $_SESSION['current_user'];
		
		// home page
		$kleinRouter->respond(array('GET','POST'), '/id/[:id]', function ($_KLEIN_REQUEST, $response) use ($curUser) {
			if ($_KLEIN_REQUEST->param('id') == $curUser->getId())
				include "../modules/my_activities.php";
			else 
				include "../modules/community.php";
			return;
		});
		
		// home page
		$kleinRouter->respond(array('GET','POST'), '/', function () {
			include "../modules/my_activities.php";
			return;
		});
		
		// home page
		$kleinRouter->respond(array('GET','POST'), '/community', function () {
			include "../modules/community.php";
			return;
		});
		
		// redirect to recent activities
		$kleinRouter->respond(array('GET','POST'), '/recent', function () {
			include "../modules/recent.php";
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
		
		// error response
		$kleinRouter->onHttpError(function ($code, $router) {
			$router->response()->body('Page not found.');
			return;
		});
		
	} else {
		// No user session is present,
		// redirect to login for private pages
		$kleinRouter->respond('@^\/(?!id\/[0-9]+)', function () {
			include "../modules/login.php";
			return;
		});
		
		// community is a public page if id is specified through GET
		$kleinRouter->respond('/id/[i:id]', function ($_KLEIN_REQUEST, $response) {
			// check to make sure the id parameter is present
			$id = $_KLEIN_REQUEST->param('id');
			if ($id){
				// display the public version of the community page
				include "../modules/community.php";
				return;
			} else {
				// redirect to login
				include "../modules/login.php";
				return;
			}
	    });
		
		// error response
		$kleinRouter->onHttpError(function ($code, $router) {
			$router->response()->body('Page not found.');
			return;
		});
	}
	
	$kleinRouter->dispatch();
	
} catch (PDOException $e){
	die("Database down");
} catch (FacebookRequestException $e){
	die("Unable to connect to Facebook");
} catch (Exception $e){
	die("Unexpected server error");
}