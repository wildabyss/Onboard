<?php 

require_once "../loading.php";

// redirect to the correct domain if necessary
$correctDomain = Utilities::GetMyDomain();
if ($_SERVER["HTTP_HOST"] != $correctDomain){
	$redirectUrl = "http://$correctDomain$_SERVER[REQUEST_URI]";
	
	header("Location: $redirectUrl");
	die();
}

// begin PHP session
session_start();

// output buffering
ob_start();

// namespaces
use Klein\Klein;
use Facebook\FacebookSession;
use Detection\MobileDetect;

try{
	// initialize Facebook
	$fb_app_id = FacebookUtilities::GetFacebookAppId();
	$fb_app_secret = FacebookUtilities::GetFacebookAppSecret();
	FacebookSession::setDefaultApplication($fb_app_id, $fb_app_secret);

	// URL router
	$kleinRouter = new Klein();
	
	// mobile browser detection
	$_MOBILE_DETECT = new MobileDetect();

	// check to make sure the user has logged in
	if (isset($_SESSION['current_user'])) {
		$curUser = $_SESSION['current_user'];
		
		// home page: default
		$kleinRouter->respond(array('GET','POST'), '/', function () use ($curUser) {
			// redirect with palatable link
			header("Location: /id/{$curUser->getId()}");
			die();
			return;
		});
		
		// home page: with userId specified
		$kleinRouter->respond(array('GET','POST'), '/id/[:id]', function ($_KLEIN_REQUEST, $response) use ($_MOBILE_DETECT) {
			include "../modules/desktop_modules/my_activities.php";
			return;
		});
		
		// home page: with userId and activityAssocId specified
		$kleinRouter->respond(array('GET','POST'), '/id/[:id]/actid/[:actid]', function ($_KLEIN_REQUEST, $response) use ($_MOBILE_DETECT) {
			include "../modules/desktop_modules/my_activities.php";
			return;
		});	
		
		// community
		$kleinRouter->respond(array('GET','POST'), '/community', function () use ($_MOBILE_DETECT) {
			include "../modules/desktop_modules/community.php";
			return;
		});
		
		// redirect to recent activities
		$kleinRouter->respond(array('GET','POST'), '/recent', function () use ($_MOBILE_DETECT) {
			include "../modules/desktop_modules/recent.php";
			return;
		});
		
		// redirect to login
		$kleinRouter->respond(array('GET','POST'), '/login', function () use ($_MOBILE_DETECT) {
			include "../modules/login.php";
			return;
		});
		
		// redirect to login
		$kleinRouter->respond(array('GET','POST'), '/logout', function () {
			include "../modules/logout.php";
			return;
		});
		
		// redirect to browse
		$kleinRouter->respond(array('GET','POST'), '/browse', function () use ($_MOBILE_DETECT) {
			include "../modules/desktop_modules/browse.php";
			return;
		});
		
		// server-side onboarding
		$kleinRouter->respond(array('GET','POST'), '/onboard/[:activity_assoc]', function ($_KLEIN_REQUEST, $response) {
			include "../modules/onboard.php";
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
		$kleinRouter->respond('@^\/(?!id\/[0-9]+)', function () use ($_MOBILE_DETECT) {
			include "../modules/login.php";
			return;
		});
		
		// my_activities is a public page if id is specified through GET
		$kleinRouter->respond('/id/[i:id]', function ($_KLEIN_REQUEST, $response) use ($_MOBILE_DETECT) {
			// display the public version of the my_activities page
			include "../modules/desktop_modules/my_activities.php";
			return;
	    });
		
		// my_activities is a public page if id and actid are specified through GET
		$kleinRouter->respond('/id/[:id]/actid/[:actid]', function ($_KLEIN_REQUEST, $response) use ($_MOBILE_DETECT) {
			// display the public version of the my_activities page
			include "../modules/desktop_modules/my_activities.php";
			return;
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