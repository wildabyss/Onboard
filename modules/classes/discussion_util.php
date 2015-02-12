<?php

use Map\DiscussionUserAssociationTableMap;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Propel;
use Propel\Runtime\Formatter\ObjectFormatter;
use Propel\Runtime\ActiveQuery\Criteria;

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
	public static function leaveDiscussion($discussionId, $activityAssocId){
		// find the association object
		$discAssocObj = DiscussionUserAssociationQuery::create()
			->filterByDiscussionId($discussionId)
			->filterByActivityUserAssociationId($activityAssocId)
			->findOne();
		
		if ($discAssocObj){
			$discAssocObj->setStatus(DiscussionUserAssociation::INACTIVE_STATUS);
			return $discAssocObj->save();
		} else {
			return false;
		}
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
			        and dua.status = :status;
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
			array(
				'actassocid'	=> $activityUserAssocId,
				'status'		=> DiscussionUserAssociation::ACTIVE_STATUS
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
		return DiscussionUserAssociationQuery::create()
			->joinActivityUserAssociation()
			->useActivityUserAssociationQuery()
				->joinUser()
				->filterByStatus(ActivityUserAssociation::ARCHIVED_STATUS, Criteria::NOT_EQUAL)
				->useUserQuery()
					->filterByStatus(User::ACTIVE_STATUS)
					->orderByDisplayName(Criteria::ASC)
				->endUse()
			->endUse()
			->filterByStatus(DiscussionUserAssociation::ACTIVE_STATUS)
			->filterByDiscussionId($discId)
			->find();
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
		// get the latest message first and check against the sessionContainer's timestamp
		$lastMsg = DiscussionMessageCacheQuery::create()
			->useDiscussionUserAssociationQuery()
				->filterByDiscussionId($discussionId)
			->endUse()
			->orderByTime(Criteria::DESC)
			->findOne();
		
		// compare timestamp
		if (!$lastMsg){
			$changedStatus = false;
			return array();
		} else{
			if (isset($sessionContainer[$discussionId]) && $sessionContainer[$discussionId]==$lastMsg->getTime()->getTimestamp()){
				$changedStatus = false;
			} else{
				$sessionContainer[$discussionId] = $lastMsg->getTime()->getTimestamp();
				$changedStatus = true;
			}
		}
		
		return $allMsgs = DiscussionMessageCacheQuery::create()
			->joinDiscussionUserAssociation()
			->useDiscussionUserAssociationQuery()
				->filterByDiscussionId($discussionId)
				->joinActivityUserAssociation()
				->useActivityUserAssociationQuery()
					->joinUser()
				->endUse()
			->endUse()
			->orderByTime(Criteria::ASC)
			->find();
	}
}