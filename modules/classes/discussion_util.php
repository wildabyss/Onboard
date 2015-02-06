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
	 * @param &$sessionContainer Session variable for storing the discussion modification timestamps
	 * @param &$changedStatus
	 * @return chat data, false if nothing has been modified
	 */
	// TODO: this should be made more efficient without reading the entire file every time
	public static function getChatMessages($discussionId, &$sessionContainer, &$changedStatus){
		// find the Discussion object
		$discussion = DiscussionQuery::create()->findOneById($discussionId);
		if (!$discussion){
			$changedStatus = false;
			return "";
		}
		
		// get file modification timestamp
		$filepath = "../discussions/".$discussion->getFileName();
		$modTime = filemtime($filepath);
		if (isset($sessionContainer[$discussionId]) && $modTime == $sessionContainer[$discussionId]){
			$changedStatus = false;
		} else {
			$sessionContainer[$discussionId] = $modTime;
			$changedStatus = true;
		}
		
		// read file
		$json_data = file_get_contents($filepath);
		return json_decode($json_data, true);
	}
}


/**
 * DiscussionPersistentUpdate object should be continuously ran on the server to update the discussions
 * @author Jimmy
 *
 */
class DiscussionPersistentUpdater{
	private $conn = null;
	public $stop = false;
	
	
	/**
	 * Constructor
	 */
	public function __construct(){
		$this->conn = Propel::getConnection(DiscussionUserAssociationTableMap::DATABASE_NAME);
	}
	
	
	/**
	 * Continuously update the Discussion files from the cache table
	 */
	public function updateDiscussions(){
		while (!$this->stop){
			// fetch cached messages
			$dataArr = DiscussionMessageCacheQuery::getCachedMessages($this->conn);
			
			// go through each row and pass into the respective files
			$maxId = 0;
			foreach ($dataArr as $rowArr){
				$maxId = $rowArr['cache_id'];
				
				// TODO: this should be made more efficient without reading/writing the whole file every time
				// read file
				$json_data = file_get_contents("../discussions/".$rowArr['file_name']);
				$chatData = json_decode($json_data, true);
				
				// append to $chatData
				$chatData['data'][] = array(
					'timestamp' => $rowArr['time'],
					'sender'	=> $rowArr['activity_user_assoc_id'],
					'message'	=> $rowArr['discussion_msg']
				);
				
				// write to file
				file_put_contents("../discussions/".$rowArr['file_name'], json_encode($chatData));
			}
			
			// delete cached records
			DiscussionMessageCacheQuery::deleteCachedMessages($maxId, $this->conn);
			
			// sleep
			usleep(100);
		}
	}
}