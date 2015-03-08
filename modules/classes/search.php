<?php

use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Propel;
use Propel\Runtime\Formatter\ObjectFormatter;
use Propel\Runtime\ActiveQuery\Criteria;
use Map\UserTableMap;
use Map\ActivityUserAssociationTableMap;

/**
 * Implements the search algorithms for Onboard
 * @author Jimmy
 *
 */
class OnboardSearch {
	
	/**
	 * Search for activities among the friends of $curUserId
	 * @param unknown $curUserId
	 * @param unknown $query
	 * @return Ambigous <\Propel\Runtime\Collection\Collection, multitype:, \Propel\Runtime\ActiveRecord\ActiveRecordInterface>
	 */
	public static function SearchForActivities($curUserId, $query){
		// format $query
		$query = strtoupper("%{$query}%");
		
		// sql request
		$conn = Propel::getReadConnection(ActivityUserAssociationTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select distinct aua.*
			from activity_user_assoc aua
			where
				aua.user_id in
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
				and upper(aua.alias) like :name
				and aua.status <> :actstatus
			order by aua.alias asc
			limit 20
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
				array(
						'userstatus1'	=> User::ACTIVE_STATUS,
						'myid1'			=> $curUserId,
						'userstatus2'	=> User::ACTIVE_STATUS,
						'myid2'			=> $curUserId,
						'name'			=> $query,
						'actstatus'		=> ActivityUserAssociation::ARCHIVED_STATUS
				));
		
		$formatter = new ObjectFormatter();
		$formatter->setClass('\ActivityUserAssociation'); //full qualified class name
		$resultArr = $formatter->format($conn->getDataFetcher($stmt));
		
		return $resultArr;
	}
	
	
	/**
	 * Search for active friends of $curUserId and return them as a Collection of User objects
	 * @param unknown $curUserId
	 * @param unknown $query
	 * @return Ambigous <\Propel\Runtime\Collection\Collection, multitype:, \Propel\Runtime\ActiveRecord\ActiveRecordInterface>
	 */
	public static function SearchForFriends($curUserId, $query){
		// format $query
		$query = strtoupper("%{$query}%");
		
		// sql request
		$conn = Propel::getReadConnection(UserTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select distinct user.*
			from user
			where
				id in
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
				and upper(display_name) like :name
			order by display_name asc
			limit 20
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
			array(
				'actstatus1'	=> User::ACTIVE_STATUS,
				'myid1'			=> $curUserId,
				'actstatus2'	=> User::ACTIVE_STATUS,
				'myid2'			=> $curUserId,
				'name'			=> $query
			));
		
		$formatter = new ObjectFormatter();
		$formatter->setClass('\User'); //full qualified class name
		$resultArr = $formatter->format($conn->getDataFetcher($stmt));
		
		return $resultArr;
	}
}