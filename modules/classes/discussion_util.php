<?php

use Map\DiscussionUserAssociationTableMap;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Propel;
use Propel\Runtime\Formatter\ObjectFormatter;
use Base\DiscussionMessageCacheQuery;

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
	 * @param array $arrActivityUserAssocs
	 * @param bool $forAll True to include everyone's interested friends in $arrActivityUserAssocId in this discussion, 
	 * False (default) only include those in the array 
	 * @throws Exception
	 * @return false if failed, otherwise return the Discussion object
	 */
	public static function createNewDiscussion($discussionName, $activityId, array $arrActivityUserAssocs, $forAll=false){
		// create discussion
		$discussionObj = new Discussion();
		$discussionObj->setActivityId($activityId);
		$discussionObj->setStatus(Discussion::ACTIVE_STATUS);
		$discussionObj->setName(trim($discussionName));
		$timestamp = time();
		$discussionObj->setDateCreated($timestamp);
	
		// set up physical file
		$fileName = $activityId."_".$timestamp."_".$arrActivityUserAssocs[0]->getId().".txt";
		$fileHandle = fopen("../discussions/$fileName", "w");
		if ($fileHandle===false)
			throw new Exception("Unable to create file");
		$discussionObj->setFileName($fileName);
	
		// set up association
		$discAssocCollection = new Collection();
		foreach ($arrActivityUserAssocs as $actUserAssocObj){
			$discAssocObj = new DiscussionUserAssociation();
			$discAssocObj->setDiscussion($discussionObj);
			$discAssocObj->setActivityUserAssociationId($actUserAssocObj->getId());
			$discAssocObj->setStatus(DiscussionUserAssociation::ACTIVE_STATUS);
			$discAssocCollection->append($discAssocObj);
			
			// if $forAll is set to true, we need to include all his/her interested friends too
			if ($forAll){
				$frdsActAssocObjs = ActivityUserAssociationQuery::getInterestedFriends($actUserAssocObj->getUserId(), $actUserAssocObj->getActivityId(), true);
				foreach ($frdsActAssocObjs as $frdActAssocObj){
					$discAssocObj = new DiscussionUserAssociation();
					$discAssocObj->setDiscussion($discussionObj);
					$discAssocObj->setActivityUserAssociationId($frdActAssocObj->getId());
					$discAssocObj->setStatus(DiscussionUserAssociation::ACTIVE_STATUS);
					$discAssocCollection->append($discAssocObj);
				}
			}
		}
		$discussionObj->setDiscussionUserAssociations($discAssocCollection);
	
		// save discussion and the associations
		if (!$discussionObj->save())
			return false;
		else
			return $discussionObj;
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
	
	
	/**
	 * Push the message to the cache table to be digested
	 * @param unknown $discAssocId
	 * @param unknown $timestamp
	 * @param unknown $msg
	 * @throws Exception
	 * @return Number of rows affected
	 */
	public static function pushMessage($discAssocObj, $timestamp, $msg){
		$messageObject = new DiscussionMessageCache();
		$messageObject->setMessage($msg);
		$messageObject->setTime($timestamp);
		$messageObject->setDiscussionUserAssociation($discAssocObj);
		return $messageObject->save();
	}
	
	
	/**
	 * Return the chat messages for a given Discussion object ID
	 * @param unknown $discussionId
	 * @return mixed
	 */
	public static function getChatMessages($discussionId){
		// find the Discussion object
		$discussion = DiscussionQuery::create()->findOneById($discussionId);
		
		// read file
		$json_data = file_get_contents("../discussions/".$discussion->getFileName());
		return json_decode($json_data, true);
	}
}


/**
 * DiscussionPersistentUpdate object should be continuously ran on the server to update the discussions
 * @author Jimmy
 *
 */
class DiscussionPersistentUpdater{
	private $conn = false;
	public $stop = false;
	
	
	/**
	 * Constructor
	 */
	public function __construct(){
		$conn = Propel::getWriteConnection(DiscussionUserAssociationTableMap::DATABASE_NAME);
	}
	
	
	/**
	 * Continuously update the Discussion files from the cache table
	 */
	public function UpdateDiscussions(){
		while (!stop){
			$arrCacheObj = DiscussionMessageCacheQuery::create()->find($this->conn);
			foreach ($arrCacheObj as $cacheObj){
				
			}
			
			// sleep
			usleep(100);
		}
	}
}