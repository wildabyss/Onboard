<?php 
	// set basic variables for layout
	$_PAGE_TITLE = Utilities::PAGE_RECENT; 
?>

<?php include "/layout/screen_header_start.php"; ?>
<?php include "/layout/screen_layout_start.php"; ?>

<!-- js inclusions -->
				
<!-- main content -->
<div class="content_column" id="column_middle">
	<div class="content_column_wrapper" id="column_wrapper_middle">
		<h1 class="page_title">Recent Activities</h1>
		
		<ul class="activity_list">
		
			<?php $recentActivityAssocs = ActivityListAssociationQuery::getRecentActivityListAssociations($_CUR_USER->getId(),50)?>
			<?php foreach ($recentActivityAssocs as $recentActivityAssoc):?>
				<?php $assocUser = $recentActivityAssoc->getUser() ?>
				
				<li class="feed_page_block" onclick="window.location.href = 'community?id=<?php echo $assocUser->getId()?>';">
					<a class="feed_profile_pic"></a>
					<span class="feed_page_block_body">
						<a class="feed_title">
							<i><?php echo $assocUser->getDisplayName()?></i> has added <b><?php echo $recentActivityAssoc->getAlias()?></b>.
						</a>
						<a class="feed_datetime"><?php echo $recentActivityAssoc->getDateAdded()->format('Y-m-d H:i:s');?></a>
					</span>
				</li>
		
			<?php endforeach;?>
		
		</ul>
	</div>
	
	<?php include "/layout/screen_footer.php"?>
</div>

<?php include "/layout/screen_layout_end.php"; ?>

<?php include "/layout/screen_header_end.php"; ?>