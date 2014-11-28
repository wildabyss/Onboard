<?php 
	// set basic variables for layout
	$_PAGE_TITLE = Utilities::PAGE_COMMUNITY; 
?>

<?php include "/layout/screen_header_start.php"; ?>
<?php include "/layout/screen_layout_start.php"; ?>

<!-- js inclusions -->
<script type="text/javascript" src="js/community.js"></script>
				
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
			
			<?php $list = $friend->getDefaultActivityList() ?>
			
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
							<p class="post_body">
								<?php echo $actAssoc->getDescription();?>
							</p>
							
							<div class="interest_info">
								<a class="interest_tally" id="interest_tally_<?php echo $actAssoc->getId()?>">
									<?php echo ActivityListAssociationQuery::countInterestedFriends($friend->getId(), $actAssoc->getActivityId())?>
									interests
								</a>
								
								<?php $userAssocLevel = ActivityListAssociationQuery::detUserAssociationWithActivity($_CUR_USER->getId(), $actAssoc->getActivityId())?>
								
								<!-- Leave/Onboard button -->
								<?php if ($userAssocLevel == ActivityListAssociation::USER_IS_OWNER):?>
									<a class="onboard_leave_unavailable">You're an owner</a>
								<?php elseif (DiscussionUserAssociationQuery::isUserInDiscussion($_CUR_USER->getId(), $actAssoc->getActivityId())):?>
									<a class="onboard_leave_unavailable">You're in discussion</a>
								<?php else:?>
									<a class="onboard_leave" type="<?php if ($userAssocLevel == ActivityListAssociation::USER_IS_ASSOCIATED):?>leave<?php else: ?>onboard<?php endif?>" 
										onclick="likeActivity(this, <?php echo $actAssoc->getId()?>, <?php echo $friend->getId()?>);">
										<?php if ($userAssocLevel == ActivityListAssociation::USER_IS_ASSOCIATED):?>Leave<?php else: ?>Onboard!<?php endif?>
									</a>
								<?php endif;?>
								
							</div>
						</li>
					<?php endfor;?>
				</ul>
			<?php endif;?>
		
		<?php else:?>
	
			<?php $_CUR_USER->getFriends($friends); ?>
			
			<h1 class="page_title">My Community</h1>
			<?php foreach ($friends as $friend):?>
				<div class="friend">
					<a class="friend_profile_pic"></a>
					<div style="vertical-align: middle; display:inline-block; height:100px; margin:0;"></div>
					<a class="friend_name comm_link" href="community?id=<?php echo $friend['id']?>"><?php echo $friend['display_name']?></a>
				</div>
			<?php endforeach; ?>
		
		<?php endif;?>
	</div>
	
	<?php include "/layout/screen_footer.php"?>
</div>

<?php include "/layout/screen_layout_end.php"; ?>

<?php include "newsfeed.php"; ?>

<?php include "/layout/screen_header_end.php"; ?>