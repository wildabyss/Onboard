<?php 
	// set variables
	if (!isset($_MY_LIST))
		$_MY_LIST = false;
	if (!isset($_IS_POPUP))
		$_IS_POPUP = false;
	
	// elements' id prefix
	if ($_IS_POPUP)
		$id_prefix = "popup_";
	else 
		$id_prefix = "";
	
	// completed status
	if ($_ACT_OBJ_VIEW->getStatus()==ActivityUserAssociation::COMPLETED_STATUS){
		$markAsCompletedClick = "$('#mark_complete_".$_ACT_OBJ_VIEW->getId()."').on('click', {actAssocId:'".$_ACT_OBJ_VIEW->getId()."'}, markAsActive);";
		$markAsCompletedText = "Mark as active";
		$liClass = "completed_activity";
	} else {
		$markAsCompletedClick = "$('#mark_complete_".$_ACT_OBJ_VIEW->getId()."').on('click', {actAssocId:'".$_ACT_OBJ_VIEW->getId()."'}, markAsCompleted);";
		$markAsCompletedText = "Mark as completed";
		$liClass = "";
	}
	
	// interest summary appearances
	$interestedFrds = ActivityUserAssociationQuery::getInterestedFriends($_FRIEND->getId(), $_ACT_OBJ_VIEW->getActivityId());
?>

<div class="activity_basic_container">
	<div id="<?php echo $id_prefix?>basic_activity_<?php echo $_ACT_OBJ_VIEW->getId()?>">
		<h2 class="activity_title <?php echo $liClass?>" id="<?php echo $id_prefix?>activity_title_<?php echo $_ACT_OBJ_VIEW->getId()?>"><?php echo htmlentities($_ACT_OBJ_VIEW->getAlias())?></h2>
		
		<?php if ($_IS_POPUP && $_MY_LIST):?>
			<a class="details" id="activity_drop_<?php echo $_ACT_OBJ_VIEW->getId()?>" onclick="displayDetailsBox(event, 'activity_edit_<?php echo $_ACT_OBJ_VIEW->getId()?>')"></a>
			<div class="details_box" id="activity_edit_<?php echo $_ACT_OBJ_VIEW->getId()?>">
				<a class="checked detail_box_item" id="mark_complete_<?php echo $_ACT_OBJ_VIEW->getId()?>"><?php echo $markAsCompletedText?></a>
				<script type="text/javascript">
					<?php echo $markAsCompletedClick?>
				</script>
				<a class="edit detail_box_item" onclick="editActivity('<?php echo $_ACT_OBJ_VIEW->getId()?>')">Edit</a>
				<a class="delete detail_box_item" onclick="deleteActivity('<?php echo $_ACT_OBJ_VIEW->getId()?>', '<?php echo $curUser->getId()?>')">Delete</a>
			</div>
		<?php endif?>
		
		<!--<?php //$cats = $_ACT_OBJ_VIEW->getActivityCategories();?>
		<span class="category_info" id="activity_categories_<?php //echo $_ACT_OBJ_VIEW->getId()?>">
			<?php //for ($j=0; $j<count($cats); $j++):?>
				[<?php //echo $cats[$j]->getCategory()->getName();?>]
			<?php //endfor;?>
		</span>-->
		
		<a class="datetime">Added: <?php echo $_ACT_OBJ_VIEW->getDateAdded()->format('Y-m-d H:i:s');?></a>
		<p class="post_body" id="<?php echo $id_prefix?>activity_description_<?php echo $_ACT_OBJ_VIEW->getId()?>">
			<?php echo htmlentities($_ACT_OBJ_VIEW->getDescription()) ?>
		</p>
	</div>
	
	<div class="interested_friends_summary_container">
		<ul class="interested_friends_summary" id="<?php echo $id_prefix?>interested_friends_summary_<?php echo $_ACT_OBJ_VIEW->getId()?>">
			<?php $numInterestedFrds = count($interestedFrds)?>
			<?php for ($iIntFrd=0; $iIntFrd<15 && $iIntFrd<$numInterestedFrds; $iIntFrd++):?>
				<?php $frdInt = $interestedFrds[$iIntFrd]?>
				<li><?php echo htmlentities($frdInt->getDisplayName()) ?></li>
			<?php endfor?>
			
			<!-- count the remnant -->
			<?php $rem = $numInterestedFrds - $iIntFrd?>
			<?php if ($rem>0):?>
				<li>+<?php echo $rem?> more...</li>
			<?php endif?>
		</ul>
	</div>
	
	<div class="interest_info" id="<?php echo $id_prefix?>interest_info_<?php echo $_ACT_OBJ_VIEW->getId()?>">
		<div class="interest_tally_container">
			<a class="interest_tally" id="<?php echo $id_prefix?>interest_tally_<?php echo $_ACT_OBJ_VIEW->getId()?>"
				onmouseover = "showInterestedFriendsSummary('<?php echo $id_prefix?>interested_friends_summary_<?php echo $_ACT_OBJ_VIEW->getId()?>')"
				onmouseout = "hideInterestedFriendsSummary('<?php echo $id_prefix?>interested_friends_summary_<?php echo $_ACT_OBJ_VIEW->getId()?>')">
				<?php echo $numInterestedFrds?> interests
			</a>
			
			<?php if (!$_MY_LIST):?>
				<!-- Leave/Onboard button -->
				<?php include "onboard_button_view.php" ?>
			<?php endif?>
		</div>
	</div>

</div>