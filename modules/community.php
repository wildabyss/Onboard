<?php 
	// set basic variables for layout
	$_PAGE_TITLE = Utilities::PAGE_COMMUNITY; 
	
	if (isset($_SESSION['current_user']))
		$curUser = $_SESSION['current_user'];
?>

<?php include "/layout/screen_header_start.php"; ?>
<?php include "/layout/screen_layout_start.php"; ?>

<!-- js inclusions -->
<script type="text/javascript" src="js/community.js"></script>
<script type="text/javascript" src="js/common.js"></script>
				
<!-- main content -->
<div class="content_column" id="column_middle">
	<div class="content_column_wrapper" id="column_wrapper_middle">
	
		<?php 
			if (isset($_GET['id'])) 
				$_FRIEND = UserQuery::create()->findOneById($_GET['id']); 
		?>
		
		<?php if (isset($_FRIEND) && $_FRIEND!=false):?>
		
			<div id="profile_section">
				<a id="profile_pic" style="background-image: url(../profile_pic_cache/<?php echo $_FRIEND->getId()?>_large.jpg)"></a>
				<h1 class="profile_name"><?php echo htmlentities($_FRIEND->getDisplayName()) ?></h1>
				<a class="user_info">Email: <?php echo htmlentities($_FRIEND->getEmail()) ?></a>
				<a class="user_info">Phone: <?php echo htmlentities($_FRIEND->getPhone()) ?></a>
			</div>
						
			<?php $activities = $_FRIEND->getActiveOrCompletedActivityAssociations() ?>
			<?php if (count($activities) == 0):?>
				<p class="no_activity_msg">This person has no activity.</p>
			<?php else:?>
		
				<ul class="activity_list">
					<?php for ($i=0; $i<count($activities); $i++):?>
						<?php $_ACT_OBJ_VIEW = $activities[$i]?>
						<?php include "layout/activity_section_view.php"?>
					<?php endfor?>
				</ul>
			<?php endif?>
		
		<?php else:?>
	
			<?php $friends = $curUser->getFriends(); ?>
			
			<h1 class="page_title">My Community</h1>
			<?php if (count($friends)==0):?>
				<p id="no_activity_msg">None of your friends is currently using <i>onboard</i>.</p>
			<?php else:?>
				<?php foreach ($friends as $friend):?>
					<div class="friend" onclick="location.href='community?id=<?php echo $friend->getId()?>'">
						<a class="friend_profile_pic" style="background-image: url(../profile_pic_cache/<?php echo $friend->getId()?>_large.jpg)"></a>
						<div class="vertical_center_filler" style="height:100px"></div>
						<a class="friend_name"><?php echo htmlentities($friend->getDisplayName())?></a>
					</div>
				<?php endforeach ?>
			<?php endif?>
		
		<?php endif?>
	</div>
	
	<?php include "/layout/screen_footer.php"?>
</div>

<?php include "/layout/screen_layout_end.php"; ?>

<?php include "/layout/screen_header_end.php"; ?>