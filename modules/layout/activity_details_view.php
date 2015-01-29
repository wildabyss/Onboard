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
		<div id="tab_strip_<?php echo $_ACT_OBJ_VIEW->getId()?>">
			<div class="discussion_tab_facebook_active discussion_tab_facebook discussion_tab" 
				onclick="facebook_switch('<?php echo $_ACT_OBJ_VIEW->getId()?>')" 
				id="facebook_tab_<?php echo $_ACT_OBJ_VIEW->getId()?>">
				<a class="discussion_tab_content">Facebook</a>
			</div>
			<?php $discussions = DiscussionUtilities::findDiscussions($_ACT_OBJ_VIEW->getId())?>
			<?php foreach ($discussions as $_DISCUSSION):?>
				<div class="discussion_tab" 
					onclick="discussion_switch('<?php echo $_DISCUSSION->getId()?>', '<?php echo $_ACT_OBJ_VIEW->getId()?>')" 
					id="discussion_tab_<?php echo $_DISCUSSION->getId()?>">
					<a class="discussion_tab_content"><?php echo $_DISCUSSION->getName()?></a>
				</div>
			<?php endforeach?>
			<div class="dicussion_tab_add discussion_tab" id="discussion_new_<?php echo $_ACT_OBJ_VIEW->getId()?>">
				<a class="discussion_tab_content">+</a>
			</div>
		</div>
		<div class="discussion_main_facebook discussion_main" id="discussion_main_<?php echo $_ACT_OBJ_VIEW->getId()?>">
			<?php include '../modules/layout/facebook_event_view.php'?>
		</div>
	</div>
</div>
