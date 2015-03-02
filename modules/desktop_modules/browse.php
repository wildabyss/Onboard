<?php 
	// set basic variables for layout
	$_PAGE_TITLE = Utilities::PAGE_BROWSE; 
	
	$curUser = $_SESSION['current_user'];
?>

<?php include "layout/screen_header_start.php"; ?>
<?php include "layout/screen_layout_start.php"; ?>

<script type="text/javascript">
	$('#global_search').focus();
</script>
				
<!-- main content -->
<div class="content_column" id="column_middle">
	<div class="content_column_wrapper" id="column_wrapper_middle">
		<h1 class="page_title">Search Onboard</h1>
		
		<?php if ($_SEARCH_QUERY != ""):?>
			<?php 
				// search for friends
				$friendsFound = OnboardSearch::SearchForFriends($curUser->getId(), $_SEARCH_QUERY);
				
				// search for activities
				$actAssocsFound = OnboardSearch::SearchForActivities($curUser->getId(), $_SEARCH_QUERY);
			?>
			
			<div class="results_container">
				<div class="results_right_container">
					<div class="results_content" id="results_friends">
						<h3>Friends</h3>
						
						<?php if ($friendsFound->count() > 0): ?>
							<?php foreach ($friendsFound as $friend):?>
								<ul class="activity_list">
									<li class="feed_page_block" style="margin-bottom: 10px"
										onclick="window.location.href = '/id/<?php echo $friend->getId()?>';">
										<a class="feed_profile_pic" style="background-image: url(/profile_pic_cache/<?php echo $friend->getId()?>_small.jpg)"></a>
										<span class="feed_page_block_body">
											<a class="vertical_center_filler" style="height:50px"></a>
											<a class="search_result_body"><?php echo htmlentities($friend->getDisplayName())?></a>
										</span>
									</li>
								</ul>
							<?php endforeach ?>
						<?php else: ?>
							<p>No results found</p>
						<?php endif ?>
					</div>
				</div>
				<div class="results_left_container">
					<div class="results_content" id="results_activities">
						<h3>Activities</h3>
						
						<?php if ($actAssocsFound->count() > 0): ?>
							<?php foreach ($actAssocsFound as $actAssoc):?>
								<ul class="activity_list">
									<li class="feed_page_block" style="margin-bottom: 10px"
										onclick="window.location.href = '/id/<?php echo $actAssoc->getUserId()?>#activity_section_<?php echo $actAssoc->getId()?>';">
										<a class="feed_profile_pic" style="background-image: url(/profile_pic_cache/<?php echo $actAssoc->getUserId()?>_small.jpg)"></a>
										<span class="feed_page_block_body">
											<a class="vertical_center_filler" style="height:50px"></a>
											<a class="search_result_body"><b><?php echo htmlentities($actAssoc->getAlias())?></b> from <i><?php echo htmlentities($actAssoc->getUser()->getDisplayName())?></i></a>
										</span>
									</li>
								</ul>
							<?php endforeach ?>
						<?php else: ?>
							<p>No results found</p>
						<?php endif ?>
					</div>
				</div>
				
			</div>
		<?php else:?>
			<p id="no_activity_msg">Search for your friends and their activities.</p>
		<?php endif ?>
	</div>
	
	<?php include "layout/screen_footer.php"?>
</div>

<?php include "layout/screen_layout_end.php"; ?>
<?php include "layout/screen_header_end.php"; ?>