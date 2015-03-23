<?php 
	// set basic variables for layout
	$_PAGE_TITLE = Utilities::PAGE_BROWSE; 
	
	$curUser = $_SESSION['current_user'];
?>

<?php include "/../layout/view_header_start.php"; ?>
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
				$actsFound = OnboardSearch::SearchForActivities($curUser->getId(), $_SEARCH_QUERY);
			?>
			
			<table class="results_container">
				<tr>
					<td class="results_content" id="results_activities">
						<h3 class="search_section_title">Activities</h3>
						
						<?php if ($actsFound->count() > 0): ?>
							<ul class="activity_list">
							<?php foreach ($actsFound as $activity):?>
								<?php $interestedFriends = ActivityUserAssociationQuery::getInterestedFriends($curUser->getId(), $activity->getId())?>
							
								<li class="feed_page_block" style="margin-bottom: 10px" id="activity_<?php echo $activity->getId()?>">
									<a class="search_result_title">
										<?php echo htmlentities($activity->getName())?>
									</a>
									
									<?php if (count($interestedFriends) == 1):?>
										<a class="search_interested_friends"><?php echo htmlentities($interestedFriends[0]->getDisplayName())?></a>
									<?php elseif (count($interestedFriends) > 1):?>
										<a class="search_interested_friends"><?php echo count($interestedFriends)?> friends</a>
									<?php endif?>
								</li>
							<?php endforeach ?>
							</ul>
						<?php else: ?>
							<p class="center">No results found</p>
						<?php endif ?>
					</td>
					
					<td class="results_content" id="results_friends">
						<h3 class="search_section_title">Friends</h3>
						
						<?php if ($friendsFound->count() > 0): ?>
							<ul class="activity_list">
							<?php foreach ($friendsFound as $friend):?>
								<li class="feed_page_block" style="margin-bottom: 10px"
									onclick="window.location.href = '/id/<?php echo $friend->getId()?>';">
									<a class="feed_profile_pic" style="background-image: url(/profile_pic_cache/<?php echo $friend->getId()?>_small.jpg)"></a>
									<span class="feed_page_block_body">
										<a class="vertical_center_filler" style="height:50px"></a>
										<a class="search_result_body"><?php echo htmlentities($friend->getDisplayName())?></a>
									</span>
								</li>
							<?php endforeach ?>
							</ul>
						<?php else: ?>
							<p class="center">No results found</p>
						<?php endif ?>
					</td>
				</tr>
			</table>
		<?php else:?>
			<p id="no_activity_msg">Search for your friends and their activities.</p>
		<?php endif ?>
	</div>
	
	<?php include "layout/screen_footer.php"?>
</div>

<?php include "layout/screen_layout_end.php"; ?>
<?php include "/../layout/view_header_end.php"; ?>