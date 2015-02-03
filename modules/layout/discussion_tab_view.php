<?php if ($_TAB_TYPE=="add"):?>
	<div class="discussion_tab dicussion_tab_add" 
		id="discussion_tab_add_<?php echo $_ACT_OBJ_VIEW->getId()?>"
		onclick="displayDetailsBox(event, 'discussion_new_<?php echo $_ACT_OBJ_VIEW->getId()?>')">
		
		<a class="discussion_tab_content">+</a>
		<div class="details_box details_box_discussion" id="discussion_new_<?php echo $_ACT_OBJ_VIEW->getId()?>">
			<a class="detail_box_item facebook" onclick="">Create Facebook group</a>
			<a class="detail_box_item discussion" onclick="discussion_add(event, '<?php echo $_ACT_OBJ_VIEW->getId()?>')">Create discussion</a>
		</div>
<?php elseif ($_TAB_TYPE=="new_discussion"):?>
	<div class="discussion_tab discussion_tab_internal discussion_tab_new" 
		id="discussion_tab_new_<?php echo $_ACT_OBJ_VIEW->getId()?>">
		
		<div class="discussion_tab_content" id="discussion_title_new_<?php echo $_ACT_OBJ_VIEW->getId()?>" 
			contenteditable="true" onkeydown="discussion_add_tab_keydown(event, '<?php echo $_ACT_OBJ_VIEW->getId()?>')">new</div>
<?php else:?>
	<div class="discussion_tab <?php if ($_TAB_TYPE=="facebook"):?>discussion_tab_facebook<?php else:?>discussion_tab_internal<?php endif?> <?php if ($_ACTIVE_TAB):?>discussion_tab_active<?php endif ?>" 
		onclick="discussion_switch('<?php echo $_DISCUSSION_OBJ->getId()?>', '<?php echo $_ACT_OBJ_VIEW->getId()?>')" 
		id="discussion_tab_<?php echo $_DISCUSSION_OBJ->getId()?>">
		
		<a class="discussion_tab_content"><?php echo $_DISCUSSION_OBJ->getName()?></a>
<?php endif ?>

</div>