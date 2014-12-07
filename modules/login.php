<?php 
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;

// set basic variables for layout
$_PAGE_TITLE = "Sign In"; 

// Facebook app info
$fb_app_id = Utilities::GetFacebookAppId();
$fb_app_secret = Utilities::GetFacebookAppSecret();
FacebookSession::setDefaultApplication($fb_app_id, $fb_app_secret);
$fbLoginHelper = new FacebookRedirectLoginHelper('http://192.168.1.126/login');

// if a valid session already exists, redirect to home
if (isset($_SESSION['fb_session']) && $_SESSION['fb_session']) {
	header("Location: mylist");
	die();
}


// get access token and finalize facebook login
try {
	$session = $fbLoginHelper->getSessionFromRedirect();
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

<?php if ($session):?>

	<?php 
	
	// verify that the user profile is in the database
	$curUserObj = UserQuery::ValidateFacebookLogin($session);
	if ($curUserObj == false){
		// register user in database
		$curUserObj = UserQuery::RegisterFacebookUser($session);
	}
	
	// try again
	if ($curUserObj != false) {
		// refresh his community based on Facebook friends
		UserCommunityAssociationQuery::PopulateCommunityFromFacebook($session, $curUserObj);
		
		// finalize authenticated session
		UserQuery::FinalizeFacebookLogin($session, $curUserObj);
		
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
					<a id="facebook_signin" href="<?php echo $fbLoginHelper->getLoginUrl(Utilities::FACEBOOK_PRIVILEGES)?>"></a>
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