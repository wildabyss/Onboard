<?php require_once("../loading.php"); ?>
<?php use Map\ActivityListAssociationTableMap; ?>

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
		
		<?php $activityLists = $user->getActivityLists(); ?>
		<?php foreach ($activityLists as $list):?>
		
			<?php if ($list->countActivityListAssociations() == 0):?>
				<p class="no_activity_msg">You currently have no activity. Add one to get started.</p>
			<?php else:?>
		
				<ul class="activity_list">
				
					<?php 
						// get activities associated with this user
						$criteria = $list->buildCriteria()->addAscendingOrderByColumn(ActivityListAssociationTableMap::COL_ALIAS);
						$activities = $list->getActivityListAssociationsJoinActivity($criteria);
					?>
					
					<?php for ($i=0; $i<count($activities); $i++):?>
						<?php $actAssoc = $activities[$i];?>
						
						<li>
							<h2><?php echo $actAssoc->getAlias();?></h2>
							<a class="details" onclick="displayDetailsBox('activity_edit_<?php echo $i;?>')"></a>
							<div class="details_box" id="activity_edit_<?php echo $i;?>">
								<a class="checked detail_box_item">Mark as completed</a>
								<a class="edit detail_box_item">Edit</a>
								<a class="delete detail_box_item">Delete</a>
							</div>
							<a class="datetime">Added: <?php echo $actAssoc->getDateAdded()->format('Y-m-d H:i:s');?></a>
							<a class="interest_tally">
								<?php echo count(ActivityListAssociationQuery::create()->filterByActivityId($actAssoc->getActivityId())->find())-1;?>
								interests
							</a>
							<p class="post_body">
								<?php echo $actAssoc->getDescription();?>
							</p>
							<div class="expand">...</div>
						</li>
					<?php endfor;?>
				</ul>
			<?php endif;?>
		<?php endforeach;?>
			
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