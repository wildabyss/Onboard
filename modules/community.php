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
				$_FRIEND = UserQuery::create()->findOneById($_GET['id']); 
		?>
		
		<?php if (isset($_FRIEND)):?>
		
			<div id="profile_section">
				<a id="profile_pic"></a>
				<h1 class="profile_name"><?php echo $_FRIEND->getDisplayName();?></h1>
				<a class="user_info">Email: <?php echo $_FRIEND->getEmail();?></a>
				<a class="user_info">Phone: <?php echo $_FRIEND->getPhone();?></a>
			</div>
			
			<?php $_ACTIVITY_LIST = $_FRIEND->getDefaultActivityList() ?>
			
			<?php $activities = $_ACTIVITY_LIST->getActiveOrCompletedActivityAssociations() ?>
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
	
			<?php $_CUR_USER->getFriends($friends); ?>
			
			<h1 class="page_title">My Community</h1>
			<?php foreach ($friends as $friend):?>
				<div class="friend">
					<a class="friend_profile_pic"></a>
					<div style="vertical-align: middle; display:inline-block; height:100px; margin:0;"></div>
					<a class="friend_name comm_link" href="community?id=<?php echo $friend['id']?>"><?php echo $friend['display_name']?></a>
				</div>
			<?php endforeach ?>
		
		<?php endif?>
	</div>
	
	<?php include "/layout/screen_footer.php"?>
</div>

<?php include "/layout/screen_layout_end.php"; ?>

<?php include "/layout/screen_header_end.php"; ?>