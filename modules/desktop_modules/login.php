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
	$fbLoginHelper = new FacebookRedirectLoginHelper($redirectUrl);
	
	// get access token and finalize facebook login
	try {
		$fbSession = $fbLoginHelper->getSessionFromRedirect();
		 
	} catch(FacebookRequestException $ex) {
		// Facebook login error
		header("Location: /login");
		die();
	} catch(\Exception $ex) {
		// generic error
		header("Location: /login");
		die();
	}
?>

<?php if ($fbSession):?>

	<?php 
	
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
	?>

<?php else:?>

	<?php include "layout/screen_header_start.php"; ?>
	
	<div id="super_global_wrapper">
		<div id="global_wrapper">
			<a id="logo"></a>
			<p class="center" id="login_slogan">
				Share your interests...<br/> 
				See what your friends want to do...<br/><br/>
				Because activities are more fun with friends. Get onboard!
			</p>
			<div id="column_login" class="content_column_wrapper">
				<h1>Sign in/Register</h1>
				<div class="center">
					<a id="facebook_signin" href="<?php echo $fbLoginHelper->getLoginUrl(FacebookUtilities::$FACEBOOK_PRIVILEGES)?>"></a>
					<!-- <p>or</p>
					<a id="google_signin"></a> -->
				</div>
			</div>
			<div id="footer_login">
				<a class="footer_block">
					Copyright <?php echo date("Y") ?>
				</a>
				<a class="footer_block" href="mailto:wildabyss@gmail.com?Subject=Onboard%20Inquiries" target="_blank">
					Contact
				</a>
			</div>
		</div>
	</div>
	
	<?php include "layout/screen_header_end.php"; ?>
	
<?php endif?>