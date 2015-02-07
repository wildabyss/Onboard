<div id="interest_details_<?php echo $_ACT_OBJ_VIEW->getId()?>" style="display:none">
	<div class="interest_details_container">
		<?php if (count($_INTERESTED_FRIENDS)>0):?>
			<ul class="interest_details">
				<?php foreach ($_INTERESTED_FRIENDS as $friend):?>
					<li onclick="window.location.href = 'community?id=<?php echo $friend->getId()?>';">
						<a class="interested_friends_summary" id="interested_friends_hint_<?php echo $_ACT_OBJ_VIEW->getId()?>_<?php echo $friend->getId()?>"><?php echo $friend->getDisplayName()?></a>
						<a class="feed_profile_pic" 
							onmouseenter="$('#interested_friends_hint_<?php echo $_ACT_OBJ_VIEW->getId()?>_<?php echo $friend->getId()?>').show()" 
							onmouseleave="$('#interested_friends_hint_<?php echo $_ACT_OBJ_VIEW->getId()?>_<?php echo $friend->getId()?>').hide()"
							style="background-image: url(../profile_pic_cache/<?php echo $friend->getId()?>_small.jpg)"></a>
					</li>
				<?php endforeach?>
			</ul>
		<?php endif?>
	</div>
	
	<div class="discussion_container">
		<?php $discussions = DiscussionUtilities::findDiscussions($_ACT_OBJ_VIEW->getId())?>
	
		<!-- tabs -->
		<div style="font-size:0" id="tab_strip_<?php echo $_ACT_OBJ_VIEW->getId()?>">
			<?php $_TAB_TYPE = "add" ?>
			<?php include "../modules/layout/discussion_tab_view.php" ?>
			
			
			<?php for ($k=0; $k<count($discussions); $k++):?>
				<?php $_DISCUSSION_OBJ = $discussions[$k] ?>
				<?php $_TAB_TYPE = "discussion" ?>
				
				<?php if ($k==0):?>
					<?php $_ACTIVE_TAB=true ?>
				<?php else:?>
					<?php $_ACTIVE_TAB=false ?>
				<?php endif ?>
				
				<?php include "../modules/layout/discussion_tab_view.php" ?>
			<?php endfor ?>
		</div>
		
		<!-- content -->
		<div class="discussion_main" id="discussion_main_<?php echo $_ACT_OBJ_VIEW->getId()?>">
			<?php if (count($discussions)==0):?>
				<p class="center">Start a discussion with the folks who have shown interest!</p>
			<?php else: ?>
				<?php $_DISCUSSION_OBJ = $discussions[0] ?>
				<?php $_CHAT_DATA = DiscussionUtilities::getChatMessages($_DISCUSSION_OBJ->getId(), $_SESSION['discussions_time'], $changed) ?>
		
				<?php include "../modules/layout/discussion_view.php" ?>
			<?php endif ?>
		</div>
	</div>
</div>
