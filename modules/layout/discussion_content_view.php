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