<?php

use Base\DiscussionQuery as BaseDiscussionQuery;
use Propel\Runtime\Collection\Collection;

/**
 * Skeleton subclass for performing query and update operations on the 'discussion' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class DiscussionQuery extends BaseDiscussionQuery
{
	/**
	 * Create a new Discussion object given the ActivityListAssociation ids (i.e. discussion participants)
	 * @param unknown $discussionName
	 * @param unknown $activityId
	 * @param array $arrActivityListAssocId
	 * @throws Exception
	 */
	public static function createNew($discussionName, $activityId, array $arrActivityListAssocId){
		// create discussion
		$discussionObj = new Discussion();
		$discussionObj->setActivityId($activityId);
		$discussionObj->setStatus(Discussion::ACTIVE_STATUS);
		$discussionObj->setName(trim($discussionName));
		$timestamp = time();
		$discussionObj->setDateCreated($timestamp);
		
		// set up physical file
		$fileName = $activityId."_".$timestamp."_".strlen($discussionName).".txt";
		$fileHandle = fopen("../discussions/$fileName", "w");
		if ($fileHandle===false)
			throw new Exception("Unable to create file");
		$discussionObj->setFileName($fileName);
		
		// set up association
		$assocCollection = new Collection();
		foreach ($arrActivityListAssocId as $actListAssocId){
			$assocObj = new DiscussionUserAssociation();
			$assocObj->setDiscussion($discussionObj);
			$assocObj->setActivityListAssociationId($actListAssocId);
			$assocObj->setStatus(DiscussionUserAssociation::ACTIVE_STATUS);
			$assocCollection->append($assocObj);
		}
		$discussionObj->setDiscussionUserAssociations($assocCollection);
		
		// save discussion and the associations
		$discussionObj->save();
	}
}
