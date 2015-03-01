<?php

// This script is used to onboard an ActivityUserAssociation without ajax

$curUser = $_SESSION['current_user'];

// retrieve the ActivityUserAssociation that's being passed in
$actAssocId = $_KLEIN_REQUEST->param('activity_assoc');
$actAssocObj = ActivityUserAssociationQuery::create()->findOneById($actAssocId);

if ($actAssocObj != false){
	// verify if the user is already associated with this activity
	$newActAssocObj = ActivityUserAssociationQuery::create()
		->filterByUserId($curUser->getId())
		->filterByActivityId($actAssocObj->getActivityId())
		->findOne();
	
	if (!$newActAssocObj){
		// onboard the user
		$newActAssocObj = $actAssocObj->copy();
		$newActAssocObj->setUser($curUser);
		$newActAssocObj->save();
	}
}

// redirect to home
header("Location: /");
die();