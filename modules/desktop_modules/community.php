<?php 
	// set basic variables for layout
	$_PAGE_TITLE = Utilities::PAGE_COMMUNITY; 
	
	if (isset($_SESSION['current_user']))
		$curUser = $_SESSION['current_user'];
	
	// get the User object that the id parameter refers to
	if (isset($_KLEIN_REQUEST) && $_KLEIN_REQUEST->param('id') !== false) {
		$_FRIEND = UserQuery::create()->findOneById($_KLEIN_REQUEST->param('id'));
	}
?>

<?php include "layout/screen_header_start.php"; ?>
<?php include "layout/screen_layout_start.php"; ?>

<!-- js inclusions -->
<script type="text/javascript" src="/js/community.js"></script>
<script type="text/javascript" src="/js/common.js"></script>
				
<!-- main content -->
<div class="content_column" id="column_middle">
	<div class="content_column_wrapper" id="column_wrapper_middle">
		
		<?php if (isset($_FRIEND) && $_FRIEND!=false):?>
			<!-- id parameter provided, retrieve the friend's info -->
		
			<div id="profile_section">
				<a id="profile_pic" style="background-image: url(/profile_pic_cache/<?php echo $_FRIEND->getId()?>_large.jpg)"></a>
				<h1 class="profile_name"><?php echo htmlentities($_FRIEND->getDisplayName()) ?></h1>
				<?php if (isset($curUser) && UserCommunityAssociationQuery::verifyUsersAreFriends($curUser->getId(), $_FRIEND->getId())):?>
					<a class="user_info">Email: <?php echo htmlentities($_FRIEND->getEmail()) ?></a>
					<a class="user_info">Phone: <?php echo htmlentities($_FRIEND->getPhone()) ?></a>
				<?php endif ?>
			</div>
			
			<?php $activities = $_FRIEND->getActiveOrCompletedActivityAssociations() ?>
			<?php if (count($activities) == 0):?>
				<p id="no_activity_msg">This person has no activity.</p>
			<?php else:?>
		
				<ul class="activity_list">
					<?php for ($i=0; $i<count($activities); $i++):?>
						<?php 
							// ActivityUserAssociation object
							$_ACT_OBJ_VIEW = $activities[$i];
						
							// decipher list onclick action
							if (isset($curUser)){
								$userAssocLevel = ActivityUserAssociationQuery::detUserAssociationWithActivity($curUser->getId(), $_ACT_OBJ_VIEW->getActivityId());
								
								if ($userAssocLevel != ActivityUserAssociation::USER_IS_NOT_ASSOCIATED){
									$listClass = 'class="activity"';
									$listAction = 'onclick="window.location.href=\'/id/'.$curUser->getId().'/actid/'.ActivityUserAssociationQuery::getObjectForUserAndActivity($curUser->getId(), $_ACT_OBJ_VIEW->getActivityId())->getId().'\'"';
								} else{
									$listClass = 'class="activity_dud"';
									$listAction = "";
								}
							} else {
								$listClass = 'class="activity_dud"';
								$listAction = "";
							}
						?>
						
						<li <?php echo $listClass?> id="activity_section_<?php echo $_ACT_OBJ_VIEW->getId()?>" <?php echo $listAction?>>
							<?php include "layout/activity_basic_view.php"?>
						</li>
					<?php endfor?>
				</ul>
			<?php endif?>
		
		<?php elseif (isset($curUser)):?>
			<!-- no id parameter provided, show the whole list of friends of the current user -->
			
			<?php $friends = $curUser->getFriends(); ?>
			
			<h1 class="page_title">My Community</h1>
			<?php if (count($friends)==0):?>
				<p id="no_activity_msg">None of your friends is currently using <i>onboard</i>.</p>
			<?php else:?>
				<?php foreach ($friends as $friend):?>
					<div class="friend" onclick="location.href='/id/<?php echo $friend->getId()?>'">
						<a class="friend_profile_pic" style="background-image: url(/profile_pic_cache/<?php echo $friend->getId()?>_large.jpg)"></a>
						<div class="vertical_center_filler" style="height:100px"></div>
						<a class="friend_name"><?php echo htmlentities($friend->getDisplayName())?></a>
					</div>
				<?php endforeach ?>
			<?php endif?>
		
		<?php else:?>
			<script type="text/javascript">
				window.location = "/";
			</script>
		<?php endif?>
	</div>
	
	<?php include "layout/screen_footer.php"?>
</div>

<?php include "layout/screen_layout_end.php"; ?>
<?php include "layout/screen_header_end.php"; ?>