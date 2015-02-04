<?php

use Base\ActivityUserAssociationQuery;
use Base\DiscussionUserAssociation;

if (!isset($_POST['action']))
	exit();

$curUser = $_SESSION['current_user'];

switch ($_POST['action']){
	case 'discussion_add':
		if (!isset($_POST['activity_assoc']))
			exit();
		
		$_TAB_TYPE = "new_discussion";
		// retrieve the activity association object
		$_ACT_OBJ_VIEW = ActivityUserAssociationQuery::create()->findOneById($_POST['activity_assoc']);
		if (!$_ACT_OBJ_VIEW)
			exit();
					
		include "../modules/layout/discussion_tab_view.php";
		break;
		
	case 'discussion_new':
		if (!isset($_POST['activity_assoc']) || !isset($_POST['name']))
			exit();
		
		// check name
		$name = trim($_POST['name']);
		if ($name=="")
			exit();

		// get the user's ActivityUserAssociation object
		$actAssocObj = ActivityUserAssociationQuery::create()->findOneById($_POST['activity_assoc']);
		if (!$actAssocObj)
			exit();
		
		try {
			// create discussion
			$_DISCUSSION_OBJ = DiscussionUtilities::createNewDiscussion($name, $actAssocObj->getActivityId(), array($actAssocObj));
			
			// output to client
			echo $_DISCUSSION_OBJ->getId();
		} catch (Exception $e){
			exit();
		}

		break;
	
	case 'discussion_switch':
		if (!isset($_POST['discussion_id']) || !isset($_POST['activity_assoc']))
			exit();
		
		// retrieve the discussion object
		$_DISCUSSION_OBJ = DiscussionQuery::create()->findOneById($_POST['discussion_id']);
		if (!$_DISCUSSION_OBJ)
			exit();
		
		// retrieve ActivityUserAssociation object
		$_ACT_OBJ_VIEW = ActivityUserAssociationQuery::create()->findOneById($_POST['activity_assoc']);
		if (!$_ACT_OBJ_VIEW)
			exit();
		
		// open file
		$_CHAT_DATA = DiscussionUtilities::getChatMessages($_POST['discussion_id'])['data'];
		
		include "../modules/layout/discussion_view.php";
		break;
		
	case "msg_add":
		if (!isset($_POST['discussion_id']) || !isset($_POST['activity_assoc']) || !isset($_POST['message']))
			exit();
			
		// fetch the DiscussionUserAssociation object
		$discAssocObj = DiscussionUserAssociationQuery::create()
			->filterByDiscussionId($_POST['discussion_id'])
			->filterByActivityUserAssociationId($_POST['activity_assoc'])
			->findOne();
		if (!$discAssocObj)
			exit();
		
		// save message
		if (DiscussionUtilities::pushMessage($discAssocObj, time(), trim($_POST['message'])))
			echo 1;
		
		break;
}