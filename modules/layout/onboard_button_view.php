<?php $curUser = $_SESSION['current_user'] ?>

<?php $userAssocLevel = ActivityUserAssociationQuery::detUserAssociationWithActivity($curUser->getId(), $_ACT_OBJ_VIEW->getActivityId())?>
<?php if ($userAssocLevel == ActivityUserAssociation::USER_IS_OWNER):?>
	<a class="onboard_leave_unavailable">You're an owner</a>
<?php elseif (DiscussionUserAssociationQuery::isUserInDiscussion($curUser->getId(), $_ACT_OBJ_VIEW->getActivityId())):?>
	<a class="onboard_leave_unavailable">You're in discussion</a>
<?php else:?>
	<a class="onboard_leave" type="<?php if ($userAssocLevel == ActivityUserAssociation::USER_IS_ASSOCIATED):?>leave<?php else: ?>onboard<?php endif?>" 
		onclick="likeActivity(event, <?php echo $_ACT_OBJ_VIEW->getId()?>);">
		<?php if ($userAssocLevel == ActivityUserAssociation::USER_IS_ASSOCIATED):?>Leave<?php else: ?>Onboard!<?php endif?>
	</a>
<?php endif?>