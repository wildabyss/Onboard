<?php

use Map\DiscussionUserAssociationTableMap;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Propel;
use Propel\Runtime\Formatter\ObjectFormatter;

/**
 * DiscussionUtilities provides static methods for accessing event discussions on the server side
 * @author Jimmy
 *
 */
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
	
	
	/**
	 * Find all the Discussion objects that match the ActivityUserAssociation
	 * @param unknown $activityUserAssocId
	 * @return array of Discussion objects
	 */
	public static function findDiscussions($activityUserAssocId){
		// search for friends
		$conn = Propel::getReadConnection(DiscussionUserAssociationTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select disc.* from discussion disc
				left join discussion_user_assoc dua
			    on dua.discussion_id = disc.id
			    where
					dua.activity_user_assoc_id = :actassocid
			        and disc.status = :status;
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
				array(
						'actassocid'	=> $activityUserAssocId,
						'status'		=> Discussion::ACTIVE_STATUS
				));
		
		$formatter = new ObjectFormatter();
		$formatter->setClass('\Discussion'); //full qualified class name
		return $formatter->format($conn->getDataFetcher($stmt));
	}
	
	
	/**
	 * Find all the DiscussionUserAssociation objects associated with a Discussion
	 * @param unknown $discId
	 * @return Array of DiscussionUserAssociation objects
	 */
	public static function findDiscussionUserAssociationsForDiscussion($discId){
		return DiscussionUserAssociationQuery::create()->findByDiscussionId($discId);
	}
	
	
	public static function pushMessage($discAssocId, $timestamp, $msg){
		
	}
	
	
	public static function displayChat($discussionId){
		// find the Discussion object
		$discussion = DiscussionQuery::create()->findOneById($discussionId);
		
		// read file
		
	}
}


/**
 * DiscussionPersistentUpdate object should be continuously ran on the server to update the discussions
 * @author Jimmy
 *
 */
class DiscussionPersistentUpdater{
	
}