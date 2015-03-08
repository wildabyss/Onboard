<?php include "/../layout/view_header_start.php"; ?>
	
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
				<a id="facebook_signin" href="<?php echo $_FB_LOGIN_HELPER->getLoginUrl(FacebookUtilities::$FACEBOOK_PRIVILEGES)?>"></a>
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

<?php include "/../layout/view_header_end.php"; ?>