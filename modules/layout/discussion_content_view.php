<ul class="message_list">
	<?php $countMsgs = count($_CHAT_DATA) ?>
	<?php $prevSender = 0 ?>
	<?php for ($i=0; $i<$countMsgs; $i++):?>
		<?php $chatBody = $_CHAT_DATA[$i]; ?>
		<?php if ($chatBody->getDiscussionUserAssociationId() != $prevSender):?>
			<?php if ($i>0):?>
			</li>
			<?php endif ?>
			
			<li>
				<div class="msg_title"><?php echo $chatBody->getDiscussionUserAssociation()->getActivityUserAssociation()->getUser()->getDisplayName() ?></div>
			<?php $prevSender = $chatBody->getDiscussionUserAssociationId() ?>
		<?php endif ?>
		
		<div class="msg_body"><?php echo $chatBody->getMessage() ?></div>
		
	<?php endfor ?>
</ul>