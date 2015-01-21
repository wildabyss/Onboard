<div class="message_container">
	<ul class="message_list">
		<?php $countMsgs = count($_CHAT_DATA) ?>
		<?php $prevSender = 0 ?>
		<?php for ($i=0; $i<$countMsgs; $i++):?>
			<?php $chatBody = $_CHAT_DATA[$i] ?>
			<?php if ($chatBody['sender'] != $prevSender):?>
				<?php if ($i>0):?>
				</li>
				<?php endif ?>
				
				<li>
					<div class="msg_title"><?php echo UserQuery::getUserForActivityUserAssociation($chatBody['sender'])->getDisplayName()?></div>
				<?php $prevSender = $chatBody['sender'] ?>
			<?php endif ?>
			
			<div class="msg_body"><?php echo $chatBody['message']?></div>
			
		<?php endfor ?>
	</ul>
</div>

<textarea rows="1" class="msg_new" id="new_msg_<?php echo $_DISCUSSION_OBJ->getId()?>"
	onkeydown="discussion_msg_keydown(event, '<?php echo $_DISCUSSION_OBJ->getId()?>', '<?php echo $_ACT_OBJ_VIEW->getId()?>')"
	onkeyup="discussion_msg_keyup(event, '<?php echo $_DISCUSSION_OBJ->getId()?>', '<?php echo $_ACT_OBJ_VIEW->getId()?>')"></textarea>