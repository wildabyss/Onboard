<?php

if (!isset($_POST['action']))
	exit();

$curUser = $_SESSION['current_user'];

switch ($_POST['action']){
	case 'facebook_switch':
		break;
	
	case 'discussion_switch':
		if (!isset($_POST['discussion_id']))
			exit();
		
		// retrieve the discussion object
		$_DISCUSSION_OBJ = DiscussionQuery::create()->findOneById($_POST['discussion_id']);
		if ($_DISCUSSION_OBJ == false)
			exit();
		
		include "../modules/layout/discussion_view.php";
		break;
}