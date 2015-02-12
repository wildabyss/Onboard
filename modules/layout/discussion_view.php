<div class="interested_friends_summary_container">
	<ul class="interested_friends_summary participants" id="participants_summary_<?php echo $_DISCUSSION_OBJ->getId()?>">
		<?php $discussionUserAssocs = DiscussionUtilities::findDiscussionUserAssociationsForDiscussion($_DISCUSSION_OBJ->getId())?>
	
		<?php foreach ($discussionUserAssocs as $discUserAssocObj):?>
			<li><?php echo htmlentities($discUserAssocObj->getActivityUserAssociation()->getUser()->getDisplayName()) ?></li>
		<?php endforeach ?>
	</ul>
</div>

<div style="text-align: right; margin: 5px;">
	<a class="button_discussions button_participants" 
		onmouseover = "showParticipants('<?php echo $_DISCUSSION_OBJ->getId()?>')"
		onmouseout = "hideParticipants('<?php echo $_DISCUSSION_OBJ->getId()?>')">Participants</a>
	<a class="button_discussions button_leave" onclick="discussion_leave('<?php echo $_DISCUSSION_OBJ->getId()?>', '<?php echo $_ACT_OBJ_VIEW->getId() ?>')">Leave</a>
</div>

<div class="message_container" id="message_container_<?php echo $_DISCUSSION_OBJ->getId() ?>">
	<?php include "../modules/layout/discussion_content_view.php" ?>
</div>

<textarea rows="1" class="msg_new" id="new_msg_<?php echo $_DISCUSSION_OBJ->getId()?>"
	onkeydown="discussion_msg_keydown(event, '<?php echo $_DISCUSSION_OBJ->getId()?>', '<?php echo $_ACT_OBJ_VIEW->getId()?>')"
	onkeyup="discussion_msg_keyup(event, '<?php echo $_DISCUSSION_OBJ->getId()?>')"></textarea>