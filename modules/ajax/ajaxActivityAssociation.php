<?php

if (!isset($_POST['action']))
	exit();

$curUser = $_SESSION['current_user'];

switch ($_POST['action']){
	// onboard/leave action
	case 'onboard':
	case 'leave':
		if (!isset($_POST['activity_assoc']) || !isset($_POST['friend_id']))
			exit();
		
		try {
			// the friend's ActivityListAssociation
			$friendActivityListAssoc = ActivityListAssociationQuery::create()
			->filterById($_POST['activity_assoc'])
			->findOne();
			// current user's (i.e. my) association level with the activity
			$userAssocLevel = ActivityListAssociationQuery::detUserAssociationWithActivity($curUser->getId(), $friendActivityListAssoc->getActivityId());
			// retrieve my default ActivityList
			$userList = $curUser->getDefaultActivityList();
		
			if ($userAssocLevel == ActivityListAssociation::USER_IS_NOT_ASSOCIATED && $_POST['action']=="onboard"){
				// find the archived version if exist
				$newActivityListAssoc = ActivityListAssociationQuery::create()
					->filterByActivityId($friendActivityListAssoc->getActivityId())
					->filterByActivityList($userList)
					->findOneOrCreate();
				
				// associate user
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
				$userActivityListAssoc->setStatus(ActivityListAssociation::ARCHIVED_STATUS);
				$userActivityListAssoc->save();
			}
		
			// send the new interest tally for this friend
			echo ActivityListAssociationQuery::countInterestedFriends($_POST['friend_id'], $friendActivityListAssoc->getActivityId());
		} catch (Exception $e){
			exit();
		}
		
		break;
	
	// get ActivityListAssociation info
	case 'get':
		if (!isset($_POST['activity_assoc']))
			exit();
		
		$actAssocObj = ActivityListAssociationQuery::create()->findPk($_POST['activity_assoc']);
		if ($actAssocObj === false)
			exit();
		$actCategories = $actAssocObj->getActivityCategories();
		
		// this variable will be used by activity_edit_view.php
		$_ACT_EDIT['id'] = $actAssocObj->getId();
		$_ACT_EDIT['alias'] = $actAssocObj->getAlias();
		$_ACT_EDIT['description'] = $actAssocObj->getDescription();
		$_ACT_EDIT['categories'] = array();
		foreach ($actCategories as $actCatObj){
			$_ACT_EDIT['categories'][] = $actCatObj->getCategory()->getName();
		}
		
		include '../modules/layout/activity_edit_view.php';
		
		break;
		
	// delete activity
	case 'delete':
		if (!isset($_POST['activity_assoc']))
			exit();
		
		$actAssocObj = ActivityListAssociationQuery::create()->findPk($_POST['activity_assoc']);
		if ($actAssocObj === false)
			exit();
		
		// change status of ActivityListAssociation object to archived (deleted)
		$actAssocObj->setStatus(ActivityListAssociation::ARCHIVED_STATUS);
		if ($actAssocObj->save() > 0)
			echo 1;
		
		break;

	// save new activity
	case 'save_new':
		if (!isset($_POST['activity_alias']) || !isset($_POST['activity_descr']) || !isset($_POST['activity_cats'])
			|| !isset($_POST['activity_list']))
			exit();
		
		// set variables required for view generation
		$_MY_LIST = true;
		
		// create the Activity object
		//FIXME need AI to associate with existing Activity objects in the db
		$actObj = new Activity();
		$actObj->setName(trim($_POST['activity_alias']));
		$actObj->save();
		
		// create the ActivityListAssociation object
		$_ACT_OBJ_VIEW = new ActivityListAssociation();
		$_ACT_OBJ_VIEW->setActivityId($actObj->getId());
		$_ACT_OBJ_VIEW->setListId($_POST['activity_list']);
		$_ACT_OBJ_VIEW->setAlias(trim($_POST['activity_alias']));
		$_ACT_OBJ_VIEW->setDescription(trim($_POST['activity_descr']));
		$_ACT_OBJ_VIEW->setDateAdded(time());
		$_ACT_OBJ_VIEW->setStatus(ActivityListAssociation::ACTIVE_STATUS);
		$_ACT_OBJ_VIEW->setIsOwner(ActivityListAssociation::USER_IS_OWNER);
		$rawCategories = explode(',', $_POST['activity_cats']);
		$_ACT_OBJ_VIEW->saveWithCategories($_ACT_OBJ_VIEW, $rawCategories);
		
		// output new HTML for client parsing
		include '../modules/layout/activity_section_view.php';
		
		break;
		
	// save existing activity
	case 'save':
		if (!isset($_POST['activity_assoc']) || !isset($_POST['activity_alias']) 
			|| !isset($_POST['activity_descr']) || !isset($_POST['activity_cats']))
			exit();

		// retrieve the existing ActivityListAssociation object
		$_MY_LIST = true;
		$_ACT_OBJ_VIEW = ActivityListAssociationQuery::create()->findPk($_POST['activity_assoc']);
		if ($_ACT_OBJ_VIEW === false)
			exit();
		
		// save the changes
		$_ACT_OBJ_VIEW->setAlias(trim($_POST['activity_alias']));
		$_ACT_OBJ_VIEW->setDescription(trim($_POST['activity_descr']));
		$rawCategories = explode(',', $_POST['activity_cats']);
		$_ACT_OBJ_VIEW->saveWithCategories($_ACT_OBJ_VIEW, $rawCategories);
		
		// output new HTML for client parsing
		include '../modules/layout/activity_section_view.php';
		
		break;
}



