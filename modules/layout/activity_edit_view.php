<li id="<?php if (isset($_ACT_EDIT['id'])):?>edit_activity_<?php echo $_ACT_EDIT['id'] ?>
	<?php else:?>adding_activity
	<?php endif?>" class="adding_activity">
	
	<input type="text" class="new_activity_input" 
		id="<?php if (isset($_ACT_EDIT['alias'])):?>edit_activity_alias_<?php echo $_ACT_EDIT['id']?>
		<?php else:?>new_activity_alias
		<?php endif?>" placeholder="Activity title"
		value = "<?php if (isset($_ACT_EDIT['alias'])):?>
			<?php echo $_ACT_EDIT['alias']?>
		<?php endif?>"
	/>
	
	<input type="text" class="new_activity_input" 
		id="<?php if (isset($_ACT_EDIT['categories'])):?>edit_activity_categories_<?php echo $_ACT_EDIT['id']?>
		<?php else:?>new_activity_categories
		<?php endif?>" placeholder="Tag Categories" 
		value = "<?php if (isset($_ACT_EDIT['categories'])):?>
			<?php for ($i=0; $i<count($_ACT_EDIT['categories']); $i++):?>
				<?php if ($i>0):?>,<?php endif?>
				<?php echo $_ACT_EDIT['categories'][$i]?>
			<?php endfor?>
		<?php endif?>"
	/>
	
	<textarea class="new_activity_text" 
		id="<?php if (isset($_ACT_EDIT['description'])):?>edit_activity_description_<?php echo $_ACT_EDIT['description']?>
		<?php else:?>new_activity_description
		<?php endif?>" placeholder="Description">
		<?php if (isset($_ACT_EDIT['description'])):?>
			<?php echo $_ACT_EDIT['description']?>
		<?php endif?>
	</textarea>
	
	<span style="float:right"><input type="button" class="new_activity_buttons" value="Save" onclick="saveNewActivity()" />
	
	<input type="button" class="new_activity_buttons" value="Cancel" onclick="cancelNewActivity()" /></span>
</li>