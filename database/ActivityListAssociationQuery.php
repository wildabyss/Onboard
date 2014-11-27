<?php

use Base\ActivityListAssociationQuery as BaseActivityListAssociationQuery;
use Map\ActivityListAssociationTableMap as ActivityListAssociationTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\Formatter\ObjectFormatter;

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
								user.status <> :actstatus1
								and uca.user_id_left = :myid1
						union
						select user.id
							from user_community_assoc uca 
							join user on uca.user_id_left = user.id 
							where
								user.status <> :actstatus2
								and uca.user_id_right = :myid2)
				order by display_name;
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
			array(
				'actid'		=> $activityId,
				'userstatus'=> User::ACTIVE_STATUS,
				':actstatus1' 	=> ActivityListAssociation::ARCHIVED_STATUS, 
				':myid1' 	=> $userId,
				':actstatus2' 	=> ActivityListAssociation::ARCHIVED_STATUS, 
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
	
	
	/**
	 * Determines whether the userId is the owner of activityId
	 * @param unknown $userId
	 * @param unknown $activityId
	 * @return boolean
	 */
	public static function isUserOwnerOfActivity($userId, $activityId){
		$conn = Propel::getReadConnection(ActivityListAssociationTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select ala.is_owner from activity_list_assoc ala
				left join activity_list list
					on list.id = ala.list_id
				where
					list.user_id = :userid
					and ala.activity_id = :actid
				limit 1;
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
			array(
				':userid'	=> $userId,
				':actid'	=> $activityId
			));
		
		// assess results
		$results = $stmt->fetchAll();
		if (count($results)==0)
			return false;
		else
			return ($results[0]['is_owner'] == 1);
	}
	
	
	
	public static function getRecentActivityListAssociations($userId, $limit){
		// search for friends
		$conn = Propel::getReadConnection(ActivityListAssociationTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select distinct ala.* from activity_list_assoc ala
				left join activity_list list
					on list.id = ala.list_id
				where 
					list.user_id in (
						select user.id
							from user_community_assoc uca 
							join user on uca.user_id_right = user.id 
							where
								user.status <> :userstatus1
								and uca.user_id_left = :myid1
						union
						select user.id
							from user_community_assoc uca 
							join user on uca.user_id_left = user.id 
							where
								user.status <> :userstatus2
								and uca.user_id_right = :myid2
					)
			        and ala.status <> :actstatus
			    order by ala.date_added desc
			    limit :limit;
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
				array(
						':userstatus1' 	=> User::INACTIVE_STATUS,
						':myid1' 	=> $userId,
						':userstatus2' 	=> User::INACTIVE_STATUS,
						':myid2' 	=> $userId,
						':actstatus'=> ActivityListAssociation::ARCHIVED_STATUS,
						':limit'	=> $limit
				));
		
		// hydrate an array of ActivityListAssociation objects
		$con = Propel::getWriteConnection(ActivityListAssociationTableMap::DATABASE_NAME);
		$formatter = new ObjectFormatter();
		$formatter->setClass('\ActivityListAssociation'); //full qualified class name
		return $formatter->format($con->getDataFetcher($stmt));
	}
}
