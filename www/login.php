<?php require_once("../loading.php"); ?>

<?php if (isset($logged_in)) {
	header("Location: index.php");
	die();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<title>Onboard</title>
		<link rel="stylesheet" href="css/main.css" type="text/css" media="screen" />
	</head>
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
</html>