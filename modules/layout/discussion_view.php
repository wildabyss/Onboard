<div class="message_container" id="message_container_<?php echo $_DISCUSSION_OBJ->getId() ?>">
	<?php include "../modules/layout/discussion_content_view.php" ?>
</div>

<textarea rows="1" class="msg_new" id="new_msg_<?php echo $_DISCUSSION_OBJ->getId()?>"
	onkeydown="discussion_msg_keydown(event, '<?php echo $_DISCUSSION_OBJ->getId()?>', '<?php echo $_ACT_OBJ_VIEW->getId()?>')"
	onkeyup="discussion_msg_keyup(event, '<?php echo $_DISCUSSION_OBJ->getId()?>')"></textarea>