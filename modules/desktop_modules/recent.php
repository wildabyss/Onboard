<?php 
	// set basic variables for layout
	$_PAGE_TITLE = Utilities::PAGE_RECENT; 
	
	$curUser = $_SESSION['current_user'];
	
	$_IS_POPUP = false;
?>

<?php include "layout/screen_header_start.php"; ?>
<?php include "layout/screen_layout_start.php"; ?>

<!-- js inclusions -->
<script type="text/javascript" src="/js/common.js"></script>
				
<!-- main content -->
<div class="content_column" id="column_middle">
	<div class="content_column_wrapper" id="column_wrapper_middle">
		<h1 class="page_title">Recent Activities</h1>
		
		<?php $recentActivityAssocs = ActivityUserAssociationQuery::getRecentActivityUserAssociations($curUser->getId(),50)?>
		<?php if (count($recentActivityAssocs)==0):?>
			<p id="no_activity_msg">No recent activity from your friends.</p>
		<?php else:?>
		
			<ul class="activity_list">
				<?php foreach ($recentActivityAssocs as $_ACT_OBJ_VIEW):?>
					<?php $assocUser = $_ACT_OBJ_VIEW->getUser() ?>
					
					<li class="activity feed_page_block" onclick="window.location.href = '/id/<?php echo $assocUser->getId()?>/actid/<?php echo $_ACT_OBJ_VIEW->getId()?>';">
						<a class="feed_profile_pic" style="background-image: url(/profile_pic_cache/<?php echo $assocUser->getId()?>_small.jpg)"></a>
						<span class="feed_page_block_body">
							<a class="feed_title">
								<i><?php echo htmlentities($assocUser->getDisplayName())?></i> has added <b><?php echo htmlentities($_ACT_OBJ_VIEW->getAlias())?></b>.
							</a>
							<a class="feed_datetime"><?php echo $_ACT_OBJ_VIEW->getDateAdded()->format('Y-m-d H:i:s');?></a>
						</span>
						
						<!-- Leave/Onboard button -->
						<?php include "layout/onboard_button_view.php" ?>
					</li>
			
				<?php endforeach?>
			</ul>
			
		<?php endif?>
	</div>
	
	<?php include "layout/screen_footer.php"?>
</div>

<?php include "layout/screen_layout_end.php"; ?>
<?php include "layout/screen_header_end.php"; ?>