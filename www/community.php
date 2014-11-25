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
	
		<?php 
			if (isset($_GET['id'])) 
				$friend = UserQuery::create()->findOneById($_GET['id']); 
		?>
		
		<?php if (isset($friend)):?>
		
			<div id="profile_section">
				<a id="profile_pic"></a>
				<h1 class="profile_name"><?php echo $friend->getDisplayName();?></h1>
				<a class="user_info">Email: <?php echo $friend->getEmail();?></a>
				<a class="user_info">Phone: <?php echo $friend->getPhone();?></a>
			</div>
			
			<?php $activityLists = $friend->getActivityLists(); ?>
			<?php foreach ($activityLists as $list):?>
			
				<?php if ($list->countActivityListAssociations() == 0):?>
					<p class="no_activity_msg">This person has no activity.</p>
				<?php else:?>
			
					<ul class="activity_list">
					
						<?php $activities = $list->getActiveOrCompletedActivityAssociations(); ?>
						<?php for ($i=0; $i<count($activities); $i++):?>
							<?php $actAssoc = $activities[$i];?>
							
							<li>
								<h2><?php echo $actAssoc->getAlias();?></h2>
								<a class="datetime">Added: <?php echo $actAssoc->getDateAdded()->format('Y-m-d H:i:s');?></a>
								<a class="interest_tally">
									<?php echo ActivityListAssociationQuery::countInterestedFriends($user->getPrimaryKey(), $actAssoc->getActivityId());?>
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
		
		<?php else:?>
	
			<?php $user->getFriends($friends); ?>
			
			<h1 class="page_title">My Community</h1>
			<?php foreach ($friends as $friend):?>
				<div class="friend">
					<a class="friend_profile_pic"></a>
					<div style="vertical-align: middle; display:inline-block; height:100px; margin:0;"></div>
					<a class="friend_name comm_link" href="community.php?id=<?php echo $friend['id']?>"><?php echo $friend['display_name']?></a>
				</div>
			<?php endforeach; ?>
		
		<?php endif;?>
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