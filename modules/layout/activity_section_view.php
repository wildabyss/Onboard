<?php $curUser = $_SESSION['current_user'];?>

<li id="activity_section_<?php echo $_ACT_OBJ_VIEW->getId()?>">
	<h2 class="activity_title" id="activity_title_<?php echo $_ACT_OBJ_VIEW->getId()?>"><?php echo $_ACT_OBJ_VIEW->getAlias()?></h2>
	
	<?php if (isset($_MY_LIST) && $_MY_LIST):?>
		<a class="details" id="activity_drop_<?php echo $_ACT_OBJ_VIEW->getId()?>" onclick="displayDetailsBox(event, 'activity_edit_<?php echo $_ACT_OBJ_VIEW->getId()?>')"></a>
		<div class="details_box" id="activity_edit_<?php echo $_ACT_OBJ_VIEW->getId()?>">
			<a class="checked detail_box_item">Mark as completed</a>
			<a class="edit detail_box_item" onclick="editActivity('<?php echo $_ACT_OBJ_VIEW->getId()?>')">Edit</a>
			<a class="delete detail_box_item" onclick="deleteActivity('<?php echo $_ACT_OBJ_VIEW->getId()?>')">Delete</a>
		</div>
	<?php endif?>
	
	<?php $cats = $_ACT_OBJ_VIEW->getActivityCategories();?>
	<span class="category_info" id="activity_categories_<?php echo $_ACT_OBJ_VIEW->getId()?>">
		<?php for ($j=0; $j<count($cats); $j++):?>
			[<?php echo $cats[$j]->getCategory()->getName();?>]
		<?php endfor;?>
	</span>
	
	<a class="datetime">Added: <?php echo $_ACT_OBJ_VIEW->getDateAdded()->format('Y-m-d H:i:s');?></a>
	<p class="post_body" id="activity_description_<?php echo $_ACT_OBJ_VIEW->getId()?>">
		<?php echo $_ACT_OBJ_VIEW->getDescription();?>
	</p>
	
	<?php 
		if (isset($_MY_LIST) && $_MY_LIST)
			$numberOfInterests = ActivityListAssociationQuery::countInterestedFriends($curUser->getPrimaryKey(), $_ACT_OBJ_VIEW->getActivityId());
		else
			$numberOfInterests = ActivityListAssociationQuery::countInterestedFriends($_FRIEND->getPrimaryKey(), $_ACT_OBJ_VIEW->getActivityId());
	?>
	<div class="interest_info">
		<a class="interest_tally" id="interest_tally_<?php echo $_ACT_OBJ_VIEW->getId()?>"><?php echo $numberOfInterests?> interests</a>
		
		<?php if (!isset($_MY_LIST) || !$_MY_LIST):?>
			<!-- Leave/Onboard button -->
			<?php $userAssocLevel = ActivityListAssociationQuery::detUserAssociationWithActivity($curUser->getId(), $_ACT_OBJ_VIEW->getActivityId())?>
			<?php if ($userAssocLevel == ActivityListAssociation::USER_IS_OWNER):?>
				<a class="onboard_leave_unavailable">You're an owner</a>
			<?php elseif (DiscussionUserAssociationQuery::isUserInDiscussion($curUser->getId(), $_ACT_OBJ_VIEW->getActivityId())):?>
				<a class="onboard_leave_unavailable">You're in discussion</a>
			<?php else:?>
				<a class="onboard_leave" type="<?php if ($userAssocLevel == ActivityListAssociation::USER_IS_ASSOCIATED):?>leave<?php else: ?>onboard<?php endif?>" 
					onclick="likeActivity(this, <?php echo $_ACT_OBJ_VIEW->getId()?>, <?php echo $_FRIEND->getId()?>);">
					<?php if ($userAssocLevel == ActivityListAssociation::USER_IS_ASSOCIATED):?>Leave<?php else: ?>Onboard!<?php endif?>
				</a>
			<?php endif?>
		<?php endif?>
	</div>
	
	<?php if (isset($_MY_LIST) && $_MY_LIST):?>
		<div class="expand">...</div>
	<?php endif?>
</li>