<?php

// This script is used to onboard an ActivityUserAssociation without ajax

$curUser = $_SESSION['current_user'];

// retrieve the ActivityUserAssociation that's being passed in
$actAssocId = $_KLEIN_REQUEST->param('activity_assoc');
$friendActivityUserAssoc = ActivityUserAssociationQuery::create()->findOneById($actAssocId);

if ($friendActivityUserAssoc != false){
	// associate user
	$newActivityAssoc = ActivityUserAssociationQuery::onboardActivity($friendActivityUserAssoc, $curUser->getId());
	
	// redirect
	header("Location: /id/{$curUser->getId()}/actid/{$newActivityAssoc->getId()}");
} else {
	// redirect to home
	header("Location: /");
}

die();