<?php require_once("../loading.php"); ?>

<?php 
	if (isset($logged_in)) {
		// Check login status, redirect to login if necessary
		header("Location: login.php");
		die();
	}
?>

<?php
	// For development testing only
	$user = UserQuery::create()
		->filterByDisplayName('Jimmy Lu')
		->findOne();
?>


<?php 
	// set basic variables for layout
	$_PAGE_TITLE = "Home"; 
?>

<?php include "../layout/screen_header_start.php"; ?>
<?php include "../layout/screen_layout_start.php"; ?>
				
<!-- main content -->
<div class="content_column" id="column_middle">
	<div class="content_column_wrapper" id="column_wrapper_middle">
		<div id="profile_section">
			<a id="profile_pic"></a>
			<h1 class="profile_name"><?php echo $user->getDisplayName();?></h1>
			<a class="user_info">Email: <?php echo $user->getEmail();?></a>
			<a class="user_info">Phone: <?php echo $user->getPhone();?></a>
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

<?php include "../layout/screen_layout_end.php"; ?>
<?php include "../layout/screen_header_end.php"; ?>