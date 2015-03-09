<?php 
	if (isset($_SESSION['current_user']))
		$curUser = $_SESSION['current_user'];

	// get the User object that the id parameter refers to
	if (isset($_KLEIN_REQUEST) && $_KLEIN_REQUEST->param('id') !== false) {
		$_FRIEND = UserQuery::create()->findOneById($_KLEIN_REQUEST->param('id'));
		
		// redirect if the supplied information is false
		if ($_FRIEND == false){
			header("Location: /community");
			die();
		}
	} else {
		// upper level would've guaranteed that the user is in session
		$_FRIEND = $curUser;
	}
	
	// am I me?
	if (isset($curUser) && $_FRIEND->getId() == $curUser->getId()){
		$_MY_LIST = true;
		$_PAGE_TITLE = Utilities::PAGE_MY_ACTIVITIES;
	} else {
		$_MY_LIST = false;
		$_PAGE_TITLE = Utilities::PAGE_COMMUNITY;
	}
	
	// get user's active activities
	$activities = $_FRIEND->getActiveOrCompletedActivityAssociations();
	
	// used for layout views
	if (isset($_KLEIN_REQUEST) && $_KLEIN_REQUEST->param('actid') !== false
		&& ActivityUserAssociationQuery::verifyUserAndActivityAssociationId($_FRIEND->getId(), $_KLEIN_REQUEST->param('actid'))) {
		$inputActId = $_KLEIN_REQUEST->param('actid');
	}
	
?>

<?php include "/../layout/view_header_start.php"; ?>
<?php include "layout/screen_layout_start.php"; ?>

<!-- js inclusions -->
<script type="text/javascript" src="/js/desktop/mylist.js"></script>

<!-- main content -->
<div class="content_column" id="column_middle">
	<div class="content_column_wrapper" id="column_wrapper_middle">
		<div id="profile_section">
			<a id="profile_pic" style="background-image: url(/profile_pic_cache/<?php echo $_FRIEND->getId()?>_large.jpg)"></a>
			<h1 class="profile_name"><?php echo htmlentities($_FRIEND->getDisplayName()) ?></h1>
			<?php if (isset($curUser)):?>
				<a class="user_info">Email: <?php echo htmlentities($_FRIEND->getEmail()) ?></a>
				<a class="user_info">Phone: <?php echo htmlentities($_FRIEND->getPhone()) ?></a>
			<?php endif?>
		</div>
		
		<?php if ($_MY_LIST):?>
			<div id="modification_bar" onclick="addNewActivity()">
				<a>Add new activity</a>
			</div>
		<?php endif ?>

		<?php if (count($activities) == 0):?>
			<?php if ($_MY_LIST):?>
				<p id="no_activity_msg">You currently have no activity. Add one to get started.</p>
			<?php else:?>
				<p id="no_activity_msg">This person doesn't have an activity list yet.</p>
			<?php endif?>
		<?php endif?>

		<ul class="activity_list" id="default_activity_list">
			<?php if ($_MY_LIST):?>
				<li id="adding_activity">
					<?php include "layout/activity_edit_view.php"?>
				</li>
			<?php endif?>

			<?php for ($i=0; $i<count($activities); $i++):?>
				<?php $_ACT_OBJ_VIEW = $activities[$i];?>
				
				<?php include "layout/activity_li_view.php"?>
			<?php endfor?>
		</ul>
	</div>
	
	<?php if (isset($inputActId)):?>
		<script type="text/javascript">
			expandActivity('<?php echo $inputActId?>', '<?php echo $_FRIEND->getId()?>', true);
		</script>
	<?php endif ?>
	
	<?php include "layout/screen_footer.php"?>
</div>

<?php include "layout/screen_layout_end.php"; ?>
<?php include "/../layout/view_header_end.php"; ?>