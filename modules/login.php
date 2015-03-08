<?php 

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookAuthorizationException;
use Base\User;

// set basic variables for layout
$_PAGE_TITLE = "Sign In"; 

// redirect URL
$requestUrl = explode('?', $_SERVER["REQUEST_URI"])[0];
if ($requestUrl == "/login")
	$requestUrl = "/";
$redirectUrl = "http://$_SERVER[HTTP_HOST]$requestUrl";

if (isset($_SESSION['current_user'])) {
	// if a valid session already exists, redirect to home
	header("Location: $redirectUrl");
	die();
	
} elseif (isset($_COOKIE['fb_token'])) {
	// if a cookie exists, validate then proceed to create session
	
	// get active facebook session
	$fbSession = new FacebookSession($_COOKIE['fb_token']);
	try {
		// validate Facebook session, if outdated or invalid, will throw exception
		if ($fbSession->validate()){
			// verify that the user profile is in the database
			$curUserObj = FacebookUtilities::CorroborateFacebookLogin($fbSession);
			if ($curUserObj){
				// finalize authenticated session
				FacebookUtilities::FinalizeFacebookLogin($fbSession, $curUserObj);
				
				// redirect to home page
				header("Location: $redirectUrl");
				die();
			}
		}
	} catch (FacebookAuthorizationException $ex){
		$fbSession = false;
	}
}

// at this point, we don't have a valid session

// Facebook login helper
$_FB_LOGIN_HELPER = new FacebookRedirectLoginHelper($redirectUrl);

// get access token and finalize facebook login
try {
	$fbSession = $_FB_LOGIN_HELPER->getSessionFromRedirect();
	 
} catch(FacebookRequestException $ex) {
	// Facebook login error
	header("Location: /login");
	die();
} catch(\Exception $ex) {
	// generic error
	header("Location: /login");
	die();
}

// now check if the Facebook login has created a valid session
if ($fbSession){
	// verify that the user profile is in the database
	$curUserObj = FacebookUtilities::CorroborateFacebookLogin($fbSession);
	if ($curUserObj == false){
		// register user in database
		$curUserObj = FacebookUtilities::RegisterFacebookUser($fbSession);
	}

	// try again
	if ($curUserObj != false) {
		// refresh his community based on Facebook friends
		UserCommunityAssociationQuery::PopulateCommunityFromFacebook($fbSession, $curUserObj);
		
		// finalize authenticated session
		FacebookUtilities::FinalizeFacebookLogin($fbSession, $curUserObj);
		
		// get profile picture
		FacebookUtilities::GetProfilePicture($fbSession, $_SESSION['current_user']);
		
		// redirect to home page
		header("Location: $redirectUrl");
		die();
	}
	
} else {
	// no valid session exists, show login screen
	
	if ($_MOBILE_DETECT->isMobile()){
		// show mobile version
		include "/mobile_modules/login_screen.php";
	} else {
		// show desktop version
		include "/desktop_modules/login_screen.php";
	}
}