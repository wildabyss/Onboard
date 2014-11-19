<?php error_reporting(E_ALL); ?>
<?php require_once("../../Onboard/Utilities.php"); ?>

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
			<?php
			echo Utilities::DeterminePlatform($_SERVER['HTTP_USER_AGENT']);
			?>
		</div>
	</body>
</html>