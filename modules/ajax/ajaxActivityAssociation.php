<?php

if (!isset($_POST['action']))
	exit();

if (isset($_SESSION['current_user']))
	$curUser = $_SESSION['current_user'];

switch ($_POST['action']){
	// onboard/leave action
	case 'onboard':
	case 'leave':
		if (!isset($curUser) || !isset($_POST['activity_assoc']))
			exit();
		
		try {
			// the friend's ActivityUserAssociation
			$friendActivityUserAssoc = ActivityUserAssociationQuery::create()->findOneById($_POST['activity_assoc']);
			
			// verify this guy is my friend
			if (!UserCommunityAssociationQuery::verifyUsersAreFriends($curUser->getId(), $friendActivityUserAssoc->getUserId()))
				exit();

			// current user's (i.e. my) association level with the activity
			$userAssocLevel = ActivityUserAssociationQuery::detUserAssociationWithActivity($curUser->getId(), $friendActivityUserAssoc->getActivityId());
			// to be returned to client later
			$assocId = 0;
			if ($userAssocLevel == ActivityUserAssociation::USER_IS_NOT_ASSOCIATED && $_POST['action']=="onboard"){
				// associate user
				$newActivityAssoc = ActivityUserAssociationQuery::onboardActivity($friendActivityUserAssoc, $curUser->getId());
				$assocId = $newActivityAssoc->getId();
		
			} elseif ($userAssocLevel == ActivityUserAssociation::USER_IS_ASSOCIATED && $_POST['action']=="leave"){
				// dissociate user
				$userActivityUserAssoc = ActivityUserAssociationQuery::create()
					->filterByActivityId($friendActivityUserAssoc->getActivityId())
					->filterByUserId($curUser->getId())
					->findOne();
				$userActivityUserAssoc->setStatus(ActivityUserAssociation::ARCHIVED_STATUS);
				$userActivityUserAssoc->save();
			}
		
			// send the new interest tally for this friend
			$interestCount = ActivityUserAssociationQuery::countInterestedFriends($friendActivityUserAssoc->getUserId(), $friendActivityUserAssoc->getActivityId());
			
			// amalgamate into an array, then return as json encoded
			$resp = array("assoc_id"=>$assocId, "user_id"=>$curUser->getId(), "interest_count"=>$interestCount);
			echo json_encode($resp);
		} catch (Exception $e){
			exit();
		}
		
		break;
	
	// get ActivityUserAssociation info and output the edit box
	case 'edit':
		if (!isset($curUser) || !isset($_POST['activity_assoc']))
			exit();
		
		// retrieve the data object
		$actAssocObj = ActivityUserAssociationQuery::create()->findOneById($_POST['activity_assoc']);
		if ($actAssocObj == false)
			exit();
		$actCategories = $actAssocObj->getActivityCategories();
		
		// verify this activityAssociation belongs to the current user
		if (!ActivityUserAssociationQuery::verifyUserAndActivityAssociationId($curUser->getId(), $actAssocObj->getId()))
			exit();
		
		// this variable will be used by activity_edit_view.php
		$_ACT_EDIT['id'] = $actAssocObj->getId();
		$_ACT_EDIT['alias'] = $actAssocObj->getAlias();
		$_ACT_EDIT['description'] = $actAssocObj->getDescription();
		$_ACT_EDIT['categories'] = array();
		foreach ($actCategories as $actCatObj){
			$_ACT_EDIT['categories'][] = $actCatObj->getCategory()->getName();
		}
		
		include '../modules/desktop_modules/layout/activity_edit_view.php';
		
		break;
		
	// delete activity
	case 'delete':
		if (!isset($curUser) || !isset($_POST['activity_assoc']))
			exit();
		
		// retrieve the data object
		$actAssocObj = ActivityUserAssociationQuery::create()->findOneById($_POST['activity_assoc']);
		if ($actAssocObj == false)
			exit();
		
		// verify this activityAssociation belongs to the current user
		if (!ActivityUserAssociationQuery::verifyUserAndActivityAssociationId($curUser->getId(), $actAssocObj->getId()))
			exit();
		
		// change status of ActivityUserAssociation object to archived (deleted)
		$actAssocObj->setStatus(ActivityUserAssociation::ARCHIVED_STATUS);
		if ($actAssocObj->save() > 0)
			echo 1;
		
		break;

	// save new activity
	case 'save_new':
		if (!isset($curUser) || !isset($_POST['activity_alias']) || !isset($_POST['activity_descr']) || !isset($_POST['activity_cats']))
			exit();
		
		// set variables required for view generation
		$_MY_LIST = true;
		$_FRIEND = $curUser;
		
		// create the Activity object
		//FIXME need AI to associate with existing Activity objects in the db
		$actObj = new Activity();
		$actObj->setName(trim($_POST['activity_alias']));
		$actObj->save();
		
		// create the ActivityUserAssociation object
		$_ACT_OBJ_VIEW = new ActivityUserAssociation();
		$_ACT_OBJ_VIEW->setActivityId($actObj->getId());
		$_ACT_OBJ_VIEW->setUserId($curUser->getId());
		$_ACT_OBJ_VIEW->setAlias(trim($_POST['activity_alias']));
		$_ACT_OBJ_VIEW->setDescription(trim($_POST['activity_descr']));
		$_ACT_OBJ_VIEW->setDateAdded(time());
		$_ACT_OBJ_VIEW->setStatus(ActivityUserAssociation::ACTIVE_STATUS);
		$_ACT_OBJ_VIEW->setIsOwner(1);
		$rawCategories = explode(',', $_POST['activity_cats']);
		$_ACT_OBJ_VIEW->saveWithCategories($_ACT_OBJ_VIEW, $rawCategories);
		
		// output new HTML for client parsing
		include '../modules/desktop_modules/layout/activity_li_view.php';
		
		break;
		
	// save existing activity
	case 'save':
		if (!isset($curUser) || !isset($_POST['activity_assoc']) || !isset($_POST['activity_alias']) 
			|| !isset($_POST['activity_descr']) || !isset($_POST['activity_cats']))
			exit();

		// retrieve the existing ActivityUserAssociation object
		$_MY_LIST = true;
		$_FRIEND = $curUser;
		$_ACT_OBJ_VIEW = ActivityUserAssociationQuery::create()->findPk($_POST['activity_assoc']);
		if ($_ACT_OBJ_VIEW == false)
			exit();
		
		// verify this activityAssociation belongs to the current user
		if (!ActivityUserAssociationQuery::verifyUserAndActivityAssociationId($curUser->getId(), $_ACT_OBJ_VIEW->getId()))
			exit();
		
		// save the changes
		$_ACT_OBJ_VIEW->setAlias(trim($_POST['activity_alias']));
		$_ACT_OBJ_VIEW->setDescription(trim($_POST['activity_descr']));
		$rawCategories = explode(',', $_POST['activity_cats']);
		$_ACT_OBJ_VIEW->saveWithCategories($_ACT_OBJ_VIEW, $rawCategories);
		
		// output new HTML for client parsing
		include '../modules/desktop_modules/layout/activity_basic_view.php';
		
		break;
	case "mark_complete":
	case "mark_active":
		if (!isset($curUser) || !isset($_POST['activity_assoc']))
			exit();
		
		// fetch associated activity
		$actAssocObj = ActivityUserAssociationQuery::create()->findPk($_POST['activity_assoc']);
		if ($actAssocObj == false)
			exit();
		
		// verify this activityAssociation belongs to the current user
		if (!ActivityUserAssociationQuery::verifyUserAndActivityAssociationId($curUser->getId(), $actAssocObj->getId()))
			exit();
		
		// set activityassoc status
		if ($_POST['action'] == "mark_complete")
			$actAssocObj->setStatus(ActivityUserAssociation::COMPLETED_STATUS);
		elseif ($_POST['action'] == "mark_active")
			$actAssocObj->setStatus(ActivityUserAssociation::ACTIVE_STATUS);
		
		// save change
		if ($actAssocObj->save() > 0)
			echo 1;
		
		break;
	case "expand_activity_details":
		// this action can be used by non-users
		if (!isset($_POST['activity_assoc']) || !isset($_POST['user_id']))
			exit();
		
		// verify the activity association id that's passed in
		if (isset($curUser) && $curUser->getId()==$_POST['user_id'])
			$_MY_LIST = true;
		else 
			$_MY_LIST = false;
		$_IS_POPUP = true;
		$_ACT_OBJ_VIEW = ActivityUserAssociationQuery::create()->findPk($_POST['activity_assoc']);
		if ($_ACT_OBJ_VIEW == false)
			exit();
		$_FRIEND = UserQuery::create()->findPk($_POST['user_id']);
		if ($_FRIEND == false)
			exit();
		
		// verify this activityAssociation belongs to the current user
		if (!ActivityUserAssociationQuery::verifyUserAndActivityAssociationId($_FRIEND->getId(), $_ACT_OBJ_VIEW->getId()))
			exit();
		
		// output interested friends
		$_INTERESTED_FRIENDS = ActivityUserAssociationQuery::getInterestedFriends($_FRIEND->getId(), $_ACT_OBJ_VIEW->getActivityId());
		include "../modules/desktop_modules/layout/activity_details_view.php";
		break;
}



