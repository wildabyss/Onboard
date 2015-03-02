<?php 
	// set basic variables for layout
	$_PAGE_TITLE = Utilities::PAGE_MY_ACTIVITIES; 
	
	// get current session user
	$curUser = $_SESSION['current_user'];
	
	// get user's active activities
	$activities = $curUser->getActiveOrCompletedActivityAssociations();
	
	// used for layout views
	$_MY_LIST = true;
	if (isset($_KLEIN_REQUEST) && $_KLEIN_REQUEST->param('actid') !== false
		&& ActivityUserAssociationQuery::verifyUserAndActivityAssociationId($curUser->getId(), $_KLEIN_REQUEST->param('actid'))) {
		$inputActAssocId = $_KLEIN_REQUEST->param('actid');
	}
?>

<?php include "layout/screen_header_start.php"; ?>
<?php include "layout/screen_layout_start.php"; ?>

<!-- js inclusions -->
<script type="text/javascript" src="/js/mylist.js"></script>
<script type="text/javascript" src="/js/common.js"></script>

<!-- main content -->
<div class="content_column" id="column_middle">
	<div class="content_column_wrapper" id="column_wrapper_middle">
		<div id="profile_section">
			<a id="profile_pic" style="background-image: url(/profile_pic_cache/<?php echo $curUser->getId()?>_large.jpg)"></a>
			<h1 class="profile_name"><?php echo htmlentities($curUser->getDisplayName()) ?></h1>
			<a class="user_info">Email: <?php echo htmlentities($curUser->getEmail()) ?></a>
			<a class="user_info">Phone: <?php echo htmlentities($curUser->getPhone()) ?></a>
		</div>
		
		<div id="modification_bar" onclick="addNewActivity()">
			<a>Add new activity</a>
		</div>

		<?php if (count($activities) == 0):?>
			<p id="no_activity_msg">You currently have no activity. Add one to get started.</p>
		<?php endif;?>

		<ul class="activity_list" id="default_activity_list">
			<li id="adding_activity">
				<?php include "layout/activity_edit_view.php"?>
			</li>

			<?php for ($i=0; $i<count($activities); $i++):?>
				<?php $_ACT_OBJ_VIEW = $activities[$i];?>
				
				<?php include "layout/activity_li_view.php"?>
			<?php endfor;?>
		</ul>
		
	</div>
	
	<?php if (isset($inputActAssocId)):?>
		<script type="text/javascript">
			expandActivity('<?php echo $inputActAssocId?>', '<?php echo $curUser->getId()?>');
		</script>
	<?php endif ?>
	
	<?php include "layout/screen_footer.php"?>
</div>

<?php include "layout/screen_layout_end.php"; ?>
<?php include "layout/screen_header_end.php"; ?>