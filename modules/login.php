<?php 
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookAuthorizationException;

// set basic variables for layout
$_PAGE_TITLE = "Sign In"; 

// Facebook app info
$fbLoginHelper = new FacebookRedirectLoginHelper('http://192.168.1.126/login');

// if a valid session already exists, redirect to home
if (isset($_SESSION['current_user']) && $_SESSION['fb_token']) {
	// get active facebook session
	$fbSession = new FacebookSession($_SESSION['fb_token']);
	try {
		// validate Facebook session, if outdated or invalid, will throw exception
		$fbSession->validate();

		// redirect
		header("Location: mylist");
		die();
	} catch (FacebookAuthorizationException $ex){
		$fbSession = false;
	}
}


// get access token and finalize facebook login
try {
	$fbSession = $fbLoginHelper->getSessionFromRedirect();
} catch(FacebookRequestException $ex) {
	// Facebook login error
	header("Location: login");
	die();
} catch(\Exception $ex) {
	// generic error
	header("Location: login");
	die();
}

?>

<?php if ($fbSession):?>

	<?php 
	
	// verify that the user profile is in the database
	$curUserObj = FacebookUtilities::ValidateFacebookLogin($fbSession);
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
		header("Location: mylist");
		die();
	}
		
	?>

<?php else:?>

	<?php include "/layout/screen_header_start.php"; ?>
	
	<body>
		<div id="global_wrapper">
			<a id="logo"></a>
			<p class="center" id="login_slogan">
				Activities are more fun with friends. Get onboard!
			</p>
			<div id="column_login" class="content_column_wrapper">
				<h1>Sign in/Register</h1>
				<div class="center">
					<a id="facebook_signin" href="<?php echo $fbLoginHelper->getLoginUrl(FacebookUtilities::FACEBOOK_PRIVILEGES)?>"></a>
					<p>or</p>
					<a id="google_signin"></a>
				</div>
			</div>
			<div id="footer_login">
				<a class="footer_block">
					Copyright 2014
				</a>
				<a class="footer_block">
					Contact
				</a>
			</div>
		</div>
	</body>
	
	<?php include "/layout/screen_header_end.php"; ?>
	
<?php endif?>