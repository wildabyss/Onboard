<?php

if (!isset($_POST['activity_assoc']) || !isset($_POST['friend_id']) || !isset($_POST['action']))
	exit();

try {
	// the friend's ActivityListAssociation
	$friendActivityListAssoc = ActivityListAssociationQuery::create()
		->filterById($_POST['activity_assoc'])
		->findOne();
	// current user's (i.e. my) association level with the activity
	$userAssocLevel = ActivityListAssociationQuery::detUserAssociationWithActivity($_CUR_USER->getId(), $friendActivityListAssoc->getActivityId());
	// retrieve my default ActivityList
	$userList = $_CUR_USER->getDefaultActivityList();

	if ($userAssocLevel == ActivityListAssociation::USER_IS_NOT_ASSOCIATED && $_POST['action']=="onboard"){
		// associate user
		$newActivityListAssoc = new ActivityListAssociation();
		$newActivityListAssoc->setActivityId($friendActivityListAssoc->getActivityId());
		$newActivityListAssoc->setActivityList($userList);
		$newActivityListAssoc->setStatus(ActivityListAssociation::ACTIVE_STATUS);
		$newActivityListAssoc->setIsOwner(0);
		$newActivityListAssoc->setDateAdded(time());
		$newActivityListAssoc->setAlias($friendActivityListAssoc->getAlias());
		$newActivityListAssoc->setDescription($friendActivityListAssoc->getDescription());
		$newActivityListAssoc->save();
		
	} elseif ($userAssocLevel == ActivityListAssociation::USER_IS_ASSOCIATED && $_POST['action']=="leave"){
		// dissociate user
		$userActivityListAssoc = ActivityListAssociationQuery::create()
			->filterByActivityId($friendActivityListAssoc->getActivityId())
			->filterByActivityList($userList)
			->findOne();
		$userActivityListAssoc->delete();
	}
	
	// send the new interest tally for this friend
	echo ActivityListAssociationQuery::countInterestedFriends($_POST['friend_id'], $friendActivityListAssoc->getActivityId());
} catch (Exception $e){
	exit();
}

?>