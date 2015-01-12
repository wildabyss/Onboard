<?php

use Map\DiscussionUserAssociationTableMap;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Propel;

class DiscussionUtilities {
	/**
	 * Create a new Discussion object given the ActivityUserAssociation ids (i.e. discussion participants)
	 * @param unknown $discussionName
	 * @param unknown $activityId
	 * @param array $arrActivityUserAssocId
	 * @throws Exception
	 * @return True if succeeded
	 */
	public static function createNewDiscussion($discussionName, $activityId, array $arrActivityUserAssocId){
		// create discussion
		$discussionObj = new Discussion();
		$discussionObj->setActivityId($activityId);
		$discussionObj->setStatus(Discussion::ACTIVE_STATUS);
		$discussionObj->setName(trim($discussionName));
		$timestamp = time();
		$discussionObj->setDateCreated($timestamp);
	
		// set up physical file
		$fileName = $activityId."_".$timestamp."_".$arrActivityUserAssocId[0].".txt";
		$fileHandle = fopen("../discussions/$fileName", "w");
		if ($fileHandle===false)
			throw new Exception("Unable to create file");
		$discussionObj->setFileName($fileName);
	
		// set up association
		$assocCollection = new Collection();
		foreach ($arrActivityUserAssocId as $actUserAssocId){
			$assocObj = new DiscussionUserAssociation();
			$assocObj->setDiscussion($discussionObj);
			$assocObj->setActivityUserAssociationId($actUserAssocId);
			$assocObj->setStatus(DiscussionUserAssociation::ACTIVE_STATUS);
			$assocCollection->append($assocObj);
		}
		$discussionObj->setDiscussionUserAssociations($assocCollection);
	
		// save discussion and the associations
		return $discussionObj->save();
	}
	
	
	/**
	 * Allows the specific user to leave the current discussion
	 * @param unknown $discussionUserAssocId
	 * @return true if succeeded
	 */
	public static function leaveDiscussion($discussionUserAssocId){
		$assocObj = DiscussionUserAssociationQuery::create()->findOneById($discussionUserAssocId);
		$assocObj->setStatus(DiscussionUserAssociation::INACTIVE_STATUS);
		
		return $assocObj->save();
	}
	
	
	public static function findDiscussionAssociation($userId, $activityId){
		
	}
	
	
	public static function pushMessage($discAssocId, $timestamp, $msg){
		
	}
}
