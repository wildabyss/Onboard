<?php

use Base\ActivityUserAssociationQuery as BaseActivityUserAssociationQuery;
use Map\ActivityUserAssociationTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
use Propel\Runtime\Formatter\ObjectFormatter;

/**
 * Skeleton subclass for performing query and update operations on the 'activity_user_assoc' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class ActivityUserAssociationQuery extends BaseActivityUserAssociationQuery
{
	
	/**
	 * Get all the friends associated with userId who are interested in activityId
	 * Only active users with active activities are be counted
	 * @param unknown $userId
	 * @param unknown $activityId
	 * @param bool $getAssocs False (default) return User objects, True return ActivityUserAssociation objects
	 * @return array of Users
	 */
	public static function getInterestedFriends($userId, $activityId, $getAssocs=false){
		// search for friends
		$conn = Propel::getReadConnection(ActivityUserAssociationTableMap::DATABASE_NAME);
		
		// stringify base SQL statement
		if ($getAssocs){
			$sql = <<<EOT
				select distinct ala.*
					from activity_user_assoc ala
EOT;
		} else {
			$sql = <<<EOT
				select distinct user.*
					from activity_user_assoc ala
				    left join
						user
				    on
						user.id = ala.user_id
EOT;
		}
		$sql = $sql.<<<EOT
			where
				ala.activity_id = :actid
		        and ala.status <> :actstatus
		        and ala.user_id in
					(select user.id
						from user_community_assoc uca
						join user on uca.user_id_right = user.id
						where
							user.status = :userstatus1
							and uca.user_id_left = :myid1
					union
					select user.id
						from user_community_assoc uca
						join user on uca.user_id_left = user.id
						where
							user.status = :userstatus2
							and uca.user_id_right = :myid2)
EOT;
		if (!$getAssocs){
			$sql = $sql." order by user.display_name";
		}
		
		// bind variables
		$stmt = $conn->prepare($sql);
		$stmt->execute(
				array(
						'actid'			=> $activityId,
						'userstatus1'	=> User::ACTIVE_STATUS,
						'userstatus2' 	=> User::ACTIVE_STATUS,
						'myid1' 		=> $userId,
						'actstatus' 	=> ActivityUserAssociation::ARCHIVED_STATUS,
						'myid2' 		=> $userId
				));
	
		$formatter = new ObjectFormatter();
		if ($getAssocs){
			$formatter->setClass('\ActivityUserAssociation');
		} else {
			$formatter->setClass('\User');
		}
		return $formatter->format($conn->getDataFetcher($stmt));
	}
	
	
	/**
	 * Count all the friends associated with userId who are interested in activityId
	 * Only active users with active activities are be counted
	 * @param unknown $userId
	 * @param unknown $activityId
	 * @return number
	 */
	public static function countInterestedFriends($userId, $activityId){
		$intFriends = self::getInterestedFriends($userId, $activityId);
		return count($intFriends);
	}
	
	
	/**
	 * Determine how the user is associated with the activity
	 * @param unknown $userId
	 * @param unknown $activityId
	 * @return number Const in ActivityUserAssociation: USER_IS_NOT_ASSOCIATED, USER_IS_ASSOCIATED, USER_IS_OWNER
	 */
	public static function detUserAssociationWithActivity($userId, $activityId){
		$conn = Propel::getReadConnection(ActivityUserAssociationTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select ala.is_owner, ala.status from activity_user_assoc ala
				where
					ala.user_id = :userid
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
		if (count($results)==0 || $results[0]['status'] == ActivityUserAssociation::ARCHIVED_STATUS)
			return ActivityUserAssociation::USER_IS_NOT_ASSOCIATED;
		else
			return ($results[0]['is_owner'] == 1 ? ActivityUserAssociation::USER_IS_OWNER : ActivityUserAssociation::USER_IS_ASSOCIATED);
	}
	
	
	/**
	 * Get a list of chronologically recent ActivityUserAssociations (i.e. friend activities) 
	 * for the given user ($userId).
	 * Limit the number of results returned to $limit.
	 * @param unknown $userId
	 * @param unknown $limit
	 * @return An array of ActivityUserAssociations
	 */
	public static function getRecentActivityUserAssociations($userId, $limit){
		// search for friends
		$conn = Propel::getReadConnection(ActivityUserAssociationTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select distinct ala.* from activity_user_assoc ala
				where
					ala.user_id in (
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
						':actstatus'=> ActivityUserAssociation::ARCHIVED_STATUS,
						':limit'	=> $limit
				));
	
		// hydrate an array of ActivityUserAssociation objects
		$formatter = new ObjectFormatter();
		$formatter->setClass('\ActivityUserAssociation'); //full qualified class name
		return $formatter->format($conn->getDataFetcher($stmt));
	}
	
	
	/**
	 * Associate the user with a friend's activity
	 * @param unknown $friendActivityUserAssoc
	 * @param unknown $curUserId
	 * @return ChildActivityUserAssociation
	 */
	public static function onboardActivity($friendActivityUserAssoc, $curUserId){
		// find the archived version if exist
		$newActivityUserAssoc = ActivityUserAssociationQuery::create()
			->filterByActivityId($friendActivityUserAssoc->getActivityId())
			->filterByUserId($curUserId)
			->findOneOrCreate();
		
		// associate user
		$newActivityUserAssoc->setActivityId($friendActivityUserAssoc->getActivityId());
		$newActivityUserAssoc->setUserId($curUserId);
		$newActivityUserAssoc->setStatus(ActivityUserAssociation::ACTIVE_STATUS);
		$newActivityUserAssoc->setIsOwner(0);
		$newActivityUserAssoc->setDateAdded(time());
		$newActivityUserAssoc->setAlias($friendActivityUserAssoc->getAlias());
		$newActivityUserAssoc->setDescription($friendActivityUserAssoc->getDescription());
		$newActivityUserAssoc->save();
		
		// to be returned to client later
		return $newActivityUserAssoc;
	}
	
	
	/**
	 * Verify that the userId and the activityUserAssociationId are linked
	 * @param unknown $userId
	 * @param unknown $activityUserAssociationId
	 * @return Return true if linked
	 */
	public static function verifyUserAndActivityAssociationId($userId, $activityUserAssociationId){
		$conn = Propel::getReadConnection(ActivityUserAssociationTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select 1
			from activity_user_assoc aua
			where
				aua.id = :associd
				and aua.user_id = :userid
				and aua.status <> :status
			limit 1;
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
				array(
						':userid'	=> $userId,
						':associd'	=> $activityUserAssociationId,
						':status'	=> ActivityUserAssociation::ARCHIVED_STATUS
				));
		
		// assess results
		$results = $stmt->fetchAll();
		return count($results);
	}
	
	
	/**
	 * Fetch the ActivityUserAssociation object for a given userId and activityId
	 * @param unknown $userId
	 * @param unknown $activityId
	 * @return ActivityUserAssociation, false if none exists
	 */
	public static function getObjectForUserAndActivity($userId, $activityId){
		return self::create()
			->filterByActivityId($activityId)
			->filterByUserId($userId)
			->filterByStatus(ActivityUserAssociation::ARCHIVED_STATUS, Criteria::NOT_EQUAL)
			->findOne();
	}
}
