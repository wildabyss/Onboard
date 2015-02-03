<?php

use Base\ActivityUserAssociationQuery;

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
		if ($_ACT_OBJ_VIEW == false)
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

		try {
			// get the user's ActivityUserAssociation object
			$actAssocObj = ActivityUserAssociationQuery::create()->findOneById($_POST['activity_assoc']);
			
			// create discussion
			$_DISCUSSION_OBJ = DiscussionUtilities::createNewDiscussion($name, $actAssocObj->getActivityId(), array($actAssocObj));
			
			echo $_DISCUSSION_OBJ->getId();
		} catch (Exception $e){
			exit();
		}

		break;
	
	case 'discussion_switch':
		if (!isset($_POST['discussion_id']))
			exit();
		
		// retrieve the discussion object
		$_DISCUSSION_OBJ = DiscussionQuery::create()->findOneById($_POST['discussion_id']);
		if ($_DISCUSSION_OBJ == false)
			exit();
		
		// open file
		$_CHAT_DATA = DiscussionUtilities::getChatMessages($_POST['discussion_id'])['data'];
		
		include "../modules/layout/discussion_view.php";
		break;
		
	case "msg_add":
		
		break;
}