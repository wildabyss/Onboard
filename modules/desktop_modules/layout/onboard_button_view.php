<?php 
	// user in session and associativity with the ActivityUserAssociation object
	if (isset($_SESSION['current_user'])) {
		$curUser = $_SESSION['current_user'];
		$userAssocLevel = ActivityUserAssociationQuery::detUserAssociationWithActivity($curUser->getId(), $_ACT_OBJ_VIEW->getActivityId());
	}
	
	// elements' id prefix
	if ($_IS_POPUP)
		$id_prefix = "popup_";
	else 
		$id_prefix = "";
?>

<?php if (isset($curUser)): ?>
	<?php if ($userAssocLevel == ActivityUserAssociation::USER_IS_OWNER):?>
		<a class="onboard_leave_unavailable">You're an owner</a>
	<?php elseif (DiscussionUserAssociationQuery::isUserInDiscussion($curUser->getId(), $_ACT_OBJ_VIEW->getActivityId())):?>
		<a class="onboard_leave_unavailable">You're in discussion</a>
	<?php else:?>
		<a id="<?php echo $id_prefix?>onboard_button_<?php echo $_ACT_OBJ_VIEW->getId()?>" 
			class="onboard_leave" type="<?php if ($userAssocLevel == ActivityUserAssociation::USER_IS_ASSOCIATED):?>leave<?php else: ?>onboard<?php endif?>" 
			onclick="likeActivity(event, '<?php echo $_ACT_OBJ_VIEW->getId()?>', '<?php echo $_ACT_OBJ_VIEW->getUserId()?>');">
			<?php if ($userAssocLevel == ActivityUserAssociation::USER_IS_ASSOCIATED):?>Leave<?php else: ?>Onboard!<?php endif?>
		</a>
	<?php endif?>
	
<?php else:?>

	<a class="onboard_leave" href="/onboard/<?php echo $_ACT_OBJ_VIEW->getId() ?>">Onboard!</a>

<?php endif ?>