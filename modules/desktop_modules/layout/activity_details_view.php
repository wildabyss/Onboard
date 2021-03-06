<?php 
	if (isset($_SESSION['current_user'])){
		$curUser = $_SESSION['current_user'];
		
		$myActivityAssocObj = ActivityUserAssociationQuery::getObjectForUserAndActivity($curUser->getId(), $_ACT_OBJ_VIEW->getActivityId());
	}
?>

<?php include "activity_basic_view.php" ?>

<div class="interest_details_container" id="interest_details_<?php echo $_ACT_OBJ_VIEW->getId()?>">
	<div class="interest_tally_details_container">
		<?php if (count($_INTERESTED_FRIENDS)>0):?>
			<ul class="interest_tally_details">
				<?php foreach ($_INTERESTED_FRIENDS as $friend):?>
					<li onclick="window.location.href = '/id/<?php echo $friend->getId()?>';">
						<a class="interested_friends_summary" id="interested_friends_hint_<?php echo $_ACT_OBJ_VIEW->getId()?>_<?php echo $friend->getId()?>"><?php echo htmlentities($friend->getDisplayName())?></a>
						<a class="feed_profile_pic" 
							onmouseenter="$('#interested_friends_hint_<?php echo $_ACT_OBJ_VIEW->getId()?>_<?php echo $friend->getId()?>').show()" 
							onmouseleave="$('#interested_friends_hint_<?php echo $_ACT_OBJ_VIEW->getId()?>_<?php echo $friend->getId()?>').hide()"
							style="background-image: url(/profile_pic_cache/<?php echo $friend->getId()?>_small.jpg)"></a>
					</li>
				<?php endforeach?>
			</ul>
		<?php endif?>
	</div>
	
	<div class="discussion_container">
		<?php if (isset($_MY_LIST) && $_MY_LIST):?>
			<?php $discussions = DiscussionUtilities::findDiscussions($_ACT_OBJ_VIEW->getId())?>
		
			<!-- tabs -->
			<div style="font-size:0" id="tab_strip_<?php echo $_ACT_OBJ_VIEW->getId()?>">
				<?php $_TAB_TYPE = "add" ?>
				<?php include "discussion_tab_view.php" ?>
				
				
				<?php for ($k=0; $k<count($discussions); $k++):?>
					<?php $_DISCUSSION_OBJ = $discussions[$k] ?>
					<?php $_TAB_TYPE = "discussion" ?>
					
					<?php if ($k==0):?>
						<?php $_ACTIVE_TAB=true ?>
					<?php else:?>
						<?php $_ACTIVE_TAB=false ?>
					<?php endif ?>
					
					<?php include "discussion_tab_view.php" ?>
				<?php endfor ?>
			</div>
			
			<!-- content -->
			<div class="discussion_main" id="discussion_main_<?php echo $_ACT_OBJ_VIEW->getId()?>">
				<?php if (count($discussions)==0):?>
					<p class="center">Start a discussion with the folks who have shown interest!</p>
				<?php else: ?>
					<?php $_DISCUSSION_OBJ = $discussions[0] ?>
					<?php $_CHAT_DATA = DiscussionUtilities::getChatMessages($_DISCUSSION_OBJ->getId(), $_SESSION['discussions_time'], $changed) ?>
			
					<?php include "discussion_view.php" ?>
				<?php endif ?>
			</div>
		<?php elseif (isset($myActivityAssocObj) && $myActivityAssocObj != false):?>
			<a id="interest_details_redirect" class="center link" href="/id/<?php echo $curUser->getId()?>/actid/<?php echo $myActivityAssocObj->getId()?>">Head over to my activity.</a>
		<?php endif?>
	</div>
</div>
