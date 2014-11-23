<?php require_once("../Utilities.php"); ?>
<?php require_once("../vendor/autoload.php"); ?>

<?php if (isset($logged_in)) {
	header("Location: login.php");
	die();
}
?>

<?php
	$firstAuthor = UserQuery::create()->findPK(1);
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
		<script type="text/javascript" src="js/main_page.js"></script>
		<div id="global_wrapper">
			<div id="header">
				<a href="index.php" id="header_logo"></a>
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
						<div id="profile_section">
							<a id="profile_pic"></a>
							<h1 id="profile_name">Jimmy Lu</h1>
						</div>
						<div class="modification_bar">
							<a class="add">New Activity</a>
						</div>
						<ul class="activity_list">
							<li>
								<h2>Rock climbing</h2>
								<a class="details" onclick="displayDetailsBox('activity_edit_1')"></a>
								<div class="details_box" id="activity_edit_1">
									<a class="checked detail_box_item">Mark as completed</a>
									<a class="edit detail_box_item">Edit</a>
									<a class="delete detail_box_item">Delete</a>
								</div>
								<a class="datetime">Added: 2014-11-22 19:44</a>
								<a class="interest_tally">5 interests</a>
								<p class="post_body">
									Rock climb at the local gyms.
								</p>
								<div class="expand">...</div>
							</li>
							<li>
								<h2>Watch Interstellar</h2>
								<p>
									Interstellar just came out! I want to watch!
								</p>
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
						New Feed
					</div>
				</div>
			</div>
		</div>
	</body>
</html>