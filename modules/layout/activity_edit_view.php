<?php 
	if (isset($_ACT_EDIT['id'])){
		$liId = "edit_activity_".$_ACT_EDIT['id']; 
	} else {
		$liId = "adding_activity";
	}
?>
<li id="<?php echo $liId?>" class="adding_activity">
	
	<?php 
		if (isset($_ACT_EDIT['alias'])){
			$titleInputId = "edit_activity_alias_".$_ACT_EDIT['id'];
		} else {
			$titleInputId = "new_activity_alias";
		}
		
		$titleInputValue = "";
		if (isset($_ACT_EDIT['alias']))
			$titleInputValue = $_ACT_EDIT['alias'];
	?>
	<input type="text" class="new_activity_input" id="<?php echo $titleInputId?>" placeholder="Activity title" value = "<?php echo $titleInputValue?>" />
	
	<?php
		if (isset($_ACT_EDIT['categories'])){
			$categoryInputId = "edit_activity_categories_".$_ACT_EDIT['id'];
		} else {
			$categoryInputId = "new_activity_categories";
		}
	
		$categoryInputValue = "";		
		if (isset($_ACT_EDIT['categories'])){
			for ($i=0; $i<count($_ACT_EDIT['categories']); $i++){
				if ($i>0)
					$categoryInputValue .= ", ";
				$categoryInputValue .= $_ACT_EDIT['categories'][$i];
			}
		}
	?>
	<input type="text" class="new_activity_input" id="<?php echo $categoryInputId?>" placeholder="Tag Categories" value = "<?php echo $categoryInputValue?>" />
	
	<?php 
		if (isset($_ACT_EDIT['description'])){
			$descrInputId = "edit_activity_description_".$_ACT_EDIT['id'];
		} else {
			$descrInputId = "new_activity_description";
		}
		
		$descrInputValue = "";
		if (isset($_ACT_EDIT['description']))
			$descrInputValue = $_ACT_EDIT['description'];
	?>
	<textarea class="new_activity_text" id="<?php echo $descrInputId?>" placeholder="Description"><?php echo $descrInputValue?></textarea>
	
	<span style="float:right"><input type="button" class="new_activity_buttons" value="Save" 
		id="<?php if (isset($_ACT_EDIT['id'])):?>save_activity_button_<?php echo $_ACT_EDIT['id']?><?php else:?>save_activity_button_new<?php endif?>"
		onclick="<?php if (isset($_ACT_EDIT['id'])):?>saveActivity('<?php echo $_ACT_EDIT['id']?>')<?php else:?>saveNewActivity('<?php echo $_ACTIVITY_LIST->getId()?>')<?php endif?>" />
	
	<input type="button" class="new_activity_buttons" value="Cancel" 
		id="<?php if (isset($_ACT_EDIT['id'])):?>cancel_activity_button_<?php echo $_ACT_EDIT['id']?><?php else:?>cancel_activity_button_new<?php endif?>"
		onclick="<?php if (isset($_ACT_EDIT['id'])):?>cancelSaveActivity('<?php echo $_ACT_EDIT['id']?>')<?php else:?>cancelNewActivity()<?php endif?>" /></span>
</li>