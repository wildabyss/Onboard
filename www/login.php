<?php require_once("../loading.php"); ?>

<?php if (isset($logged_in)) {
	header("Location: index.php");
	die();
}
?>


<?php 
	// set basic variables for layout
	$_PAGE_TITLE = "Sign In"; 
?>

<?php include "../layout/screen_header_start.php"; ?>

<body>
	<div id="global_wrapper">
		<a id="logo"></a>
		<p class="center" id="login_slogan">
			Activities are more fun with friends. Get onboard!
		</p>
		<div id="column_login" class="content_column_wrapper">
			<h1>Sign in/Register</h1>
			<div class="center">
				<a id="facebook_signin"></a>
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

<?php include "../layout/screen_header_end.php"; ?>