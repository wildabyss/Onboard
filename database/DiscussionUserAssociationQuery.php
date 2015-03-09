<?php

use Base\DiscussionUserAssociationQuery as BaseDiscussionUserAssociationQuery;
use Map\DiscussionUserAssociationTableMap;
use Propel\Runtime\Propel;

/**
 * Skeleton subclass for performing query and update operations on the 'discussion_user_assoc' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class DiscussionUserAssociationQuery extends BaseDiscussionUserAssociationQuery
{
	/**
	 * Determines whether the user indicated by userId has any discussions related to
	 * activityId
	 * @param unknown $userId
	 * @param unknown $activityId
	 * @return True if the user indicated by userId is discussing activityId
	 */
	public static function isUserInDiscussion($userId, $activityId){
		$conn = Propel::getReadConnection(DiscussionUserAssociationTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select 1 from discussion_user_assoc dua
			    left join activity_user_assoc aua
					on aua.id = dua.activity_user_assoc_id
			    where
					aua.user_id = :userid 
					and aua.activity_id = :actid
					and aua.status <> :actstatus
					and dua.status = :discstatus
				limit 1;
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
			array(
				':userid'	=> $userId,
				':actid'	=> $activityId,
				':actstatus'=> ActivityUserAssociation::ARCHIVED_STATUS,
				':discstatus' => DiscussionUserAssociation::ACTIVE_STATUS
			));
	
		// assess results
		$results = $stmt->fetchAll();
		return count($results)>0;
	}
	
	
	/**
	 * Verify that the userId and the discussionId are linked
	 * @param unknown $userId
	 * @param unknown $discussionId
	 * @return Return true if linked
	 */
	public static function verifyUserAndDiscussionId($userId, $discussionId){
		$conn = Propel::getReadConnection(DiscussionUserAssociationTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select 1
			from 
				discussion_user_assoc dua
			left join
				activity_user_assoc aua
			on
				dua.activity_user_assoc_id = aua.id
			where
				dua.discussion_id = :discid
				and aua.user_id = :userid
			limit 1;
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
			array(
				':userid'	=> $userId,
				':discid'	=> $discussionId
			));
	
		// assess results
		$results = $stmt->fetchAll();
		return count($results);
	}
}
