<?php

use Base\ActivityListAssociationQuery as BaseActivityListAssociationQuery;
use Map\ActivityListAssociationTableMap as ActivityListAssociationTableMap;
use Propel\Runtime\Propel;

/**
 * Skeleton subclass for performing query and update operations on the 'activity_list_assoc' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class ActivityListAssociationQuery extends BaseActivityListAssociationQuery
{
	/**
	 * Get all the friends associated with userId who are interested in activityId
	 * Only active users with active activities are be counted
	 * @param unknown $userId
	 * @param unknown $activityId
	 * @param unknown $results
	 */
	public static function getInterestedFriends($userId, $activityId, &$results){
		$results = array();
		
		// search for friends
		$conn = Propel::getReadConnection(ActivityListAssociationTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select distinct user.id, user.display_name 
				from activity_list_assoc ala
				left join 
					activity_list al
			    on 
					al.id = ala.list_id
			    left join 
					user
			    on 
					user.id = al.user_id
				where 
					ala.activity_id = :actid
			        and ala.status = :userstatus
			        and user.id in 
						(select user.id
							from user_community_assoc uca 
							join user on uca.user_id_right = user.id 
							where
								user.status = :actstatus1
								and uca.user_id_left = :myid1
						union
						select user.id
							from user_community_assoc uca 
							join user on uca.user_id_left = user.id 
							where
								user.status = :actstatus2
								and uca.user_id_right = :myid2)
				order by display_name;
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
			array(
				'actid'		=> $activityId,
				'userstatus'=> User::ACTIVE_STATUS,
				':actstatus1' 	=> ActivityListAssociation::ACTIVE_STATUS, 
				':myid1' 	=> $userId,
				':actstatus2' 	=> ActivityListAssociation::ACTIVE_STATUS, 
				':myid2' 	=> $userId
				));
		
		// populate results array
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$results[] = $row;
		}
	}
	
	
	/**
	 * Count all the friends associated with userId who are interested in activityId
	 * Only active users with active activities are be counted
	 * @param unknown $userId
	 * @param unknown $activityId
	 * @return number
	 */
	public static function countInterestedFriends($userId, $activityId){
		self::getInterestedFriends($userId, $activityId, $results);
		return count($results);
	} 
} // ActivityListAssociationQuery
