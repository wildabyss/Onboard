<?php

use Base\ActivityUserAssociationQuery;

if (!isset($_POST['action']))
	exit();

$curUser = $_SESSION['current_user'];

switch ($_POST['action']){
	case 'facebook_switch':
		include "../modules/layout/facebook_event_view.php";
		break;
	
	case 'discussion_switch':
		if (!isset($_POST['discussion_id']) || !isset($_POST['activity_assoc']))
			exit();
		
		// retrieve the discussion object
		$_DISCUSSION_OBJ = DiscussionQuery::create()->findOneById($_POST['discussion_id']);
		if ($_DISCUSSION_OBJ == false)
			exit();
		// retrieve the activity association object
		$_ACT_OBJ_VIEW = ActivityUserAssociationQuery::create()->findOneById($_POST['activity_assoc']);
		if ($_ACT_OBJ_VIEW == false)
			exit();
		
		// open file
		$_CHAT_DATA = DiscussionUtilities::getChatMessages($_POST['discussion_id'])['data'];
		
		include "../modules/layout/discussion_view.php";
		break;
}