<?php require_once("../loading.php"); ?>

<?php 
	if (isset($logged_in)) {
		// Check login status, redirect to login if necessary
		header("Location: login.php");
		die();
	}
?>


<?php 
	// set basic variables for layout
	$_PAGE_TITLE = "Browse Activities"; 
?>

<?php include "../layout/screen_header_start.php"; ?>
<?php include "../layout/screen_layout_start.php"; ?>
				
<!-- main content -->
<div class="content_column" id="column_middle">
	<div class="content_column_wrapper" id="column_wrapper_middle">
		
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