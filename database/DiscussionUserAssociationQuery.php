<?php

use Base\DiscussionUserAssociationQuery as BaseDiscussionUserAssociationQuery;
use Map\DiscussionUserAssociationTableMap as DiscussionUserAssociationTableMap;
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
				left join user
					on user.id = dua.user_id
			    left join discussion d
					on d.id = dua.discussion_id
				left join activity_list_assoc ala
					on ala.activity_id = d.activity_id
			    where
					user.id = :userid 
					and d.activity_id = :actid
					and ala.status = :actstatus
				limit 1;
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
			array(
				':userid'	=> $userId,
				':actid'	=> $activityId,
				':actstatus'=> ActivityListAssociation::ARCHIVED_STATUS
			));
	
		// assess results
		$results = $stmt->fetchAll();
		return count($results)>0;
	}
	
	
	public static function createNew(ActivityList $actList, array $arrParticipantsId){
		
	}
}
