<?php 
	// set basic variables for layout
	$_PAGE_TITLE = Utilities::PAGE_MY_ACTIVITIES; 
	
	$curUser = $_SESSION['current_user'];
?>

<?php include "layout/screen_header_start.php"; ?>
<?php include "layout/screen_layout_start.php"; ?>

<!-- js inclusions -->
<script type="text/javascript" src="js/mylist.js"></script>
<script type="text/javascript" src="js/common.js"></script>
				
<!-- main content -->
<div class="content_column" id="column_middle">
	<div class="content_column_wrapper" id="column_wrapper_middle">
		<div id="profile_section">
			<a id="profile_pic" style="background-image: url(../profile_pic_cache/<?php echo $curUser->getId()?>_large.jpg)"></a>
			<h1 class="profile_name"><?php echo $curUser->getDisplayName();?></h1>
			<a class="user_info">Email: <?php echo $curUser->getEmail();?></a>
			<a class="user_info">Phone: <?php echo $curUser->getPhone();?></a>
		</div>
		
		<div id="modification_bar" onclick="addNewActivity()">
			<a>Add new activity</a>
		</div>
		
		<?php $_ACTIVITY_LIST = $curUser->getDefaultActivityList(); ?>
		<?php $activities = $_ACTIVITY_LIST->getActiveOrCompletedActivityAssociations(); ?>
		
		<?php if (count($activities) == 0):?>
			<p id="no_activity_msg">You currently have no activity. Add one to get started.</p>
		<?php endif;?>
	
		<?php $_MY_LIST = true ?>
		<ul class="activity_list" id="default_activity_list">
			<?php include "layout/activity_edit_view.php"?>
		
			<?php for ($i=0; $i<count($activities); $i++):?>
				<?php $_ACT_OBJ_VIEW = $activities[$i];?>
				<?php include "layout/activity_section_view.php"?>
			<?php endfor;?>
		</ul>
		
	</div>
	
	<?php include "layout/screen_footer.php"?>
</div>

<?php include "layout/screen_layout_end.php"; ?>

<?php include "layout/screen_header_end.php"; ?>
