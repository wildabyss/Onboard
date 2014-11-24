<?php require_once("../loading.php"); ?>

<?php 
	if (isset($logged_in)) {
		// Check login status, redirect to login if necessary
		header("Location: login.php");
		die();
	}
?>

<?php
	// FIXME: For development testing only
	$user = UserQuery::create()
		->filterByDisplayName('Jimmy Lu')
		->findOne();
?>


<?php 
	// set basic variables for layout
	$_PAGE_TITLE = "Community"; 
?>

<?php include "../layout/screen_header_start.php"; ?>
<?php include "../layout/screen_layout_start.php"; ?>
				
<!-- main content -->
<div class="content_column" id="column_middle">
	<div class="content_column_wrapper" id="column_wrapper_middle">
		<?php $user->getFriends($friends); ?>
		
		<h1 class="page_title">My Community</h1>
		<?php foreach ($friends as $friend):?>
			<div class="friend">
				<a class="friend_profile_pic"></a>
				<div style="vertical-align: middle; display:inline-block; height:100px; margin:0;"></div>
				<a class="friend_name comm_link" href="community.php?id=<?php echo $friend['id']?>"><?php echo $friend['display_name']?></a>
			</div>
		<?php endforeach; ?>
	</div>
	
	<?php include "../layout/screen_footer.php"?>
</div>

<!-- news feed -->
<div class="content_column" id="column_right">
	<div class="content_column_wrapper" id="column_wrapper_right">
		New Feed
	</div>
</div>

<?php include "../layout/screen_layout_end.php"; ?>
<?php include "../layout/screen_header_end.php"; ?>