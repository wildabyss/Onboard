<?php 
	// set basic variables for layout
	$_PAGE_TITLE = Utilities::PAGE_COMMUNITY; 
	
	if (isset($_SESSION['current_user']))
		$curUser = $_SESSION['current_user'];
	
	// list of friends of the current user
	$friends = $curUser->getFriends();
?>

<?php include "/../layout/view_header_start.php"; ?>
<?php include "layout/screen_layout_start.php"; ?>

<!-- js inclusions -->
<script type="text/javascript" src="/js/desktop/community.js"></script>
				
<!-- main content -->
<div class="content_column" id="column_middle">
	<div class="content_column_wrapper" id="column_wrapper_middle">
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
	</div>
	
	<?php include "layout/screen_footer.php"?>
</div>

<?php include "layout/screen_layout_end.php"; ?>
<?php include "/../layout/view_header_end.php"; ?>