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
			<div id="header">
				<a id="header_logo"></a>
				<input class="search" id="global_search" />
				<a id="header_options"></a>
			</div>
			
			<div id="content_wrapper">
				<!-- navigation -->
				<div class="content_column" id="column_left">
					<div class="content_column_wrapper" id="column_wrapper_left">
						<ul>
							<li class="button_middle"><a id="button_home">Home</a></li>
							<li class="button_middle"><a id="button_community">Community</a></li>
							<li><a id="button_list">Browse Activities</a></li>
						</ul>
					</div>
				</div>
				
				<!-- main content -->
				<div class="content_column" id="column_middle">
					<div class="content_column_wrapper" id="column_wrapper_middle">
						<ul class="activity_list">
							<li>
								Activity 1
							</li>
							<li>
								Activity 2
							</li>
						</ul>
					</div>
					
					<div id="footer">
						<a class="footer_block">
							Copyright 2014
						</a>
						<a class="footer_block">
							Contact
						</a>
					</div>
				</div>
				
				<!-- news feed -->
				<div class="content_column" id="column_right">
					<div class="content_column_wrapper" id="column_wrapper_right">
						Right
					</div>
				</div>
			</div>
		</div>
	</body>
</html>