<?php

use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Base\UserCommunityAssociationQuery as BaseUserCommunityAssociationQuery;
use Map\UserCommunityAssociationTableMap;
use Propel\Runtime\Propel;

/**
 * Skeleton subclass for performing query and update operations on the 'user_community_assoc' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class UserCommunityAssociationQuery extends BaseUserCommunityAssociationQuery
{
	public static function PopulateCommunityFromFacebook(FacebookSession $fbSession, User $curUser){
		// get Facebook friends and return as a graph object
		// note that this only returns all the friends in his friendlist who also uses the app
		$friends = (new FacebookRequest($fbSession, 'GET', '/me/friends'))
			->execute()->getGraphObject();
		
		// convert to array
		$graphObjArray = $friends->asArray();
		$conn = Propel::getReadConnection(UserCommunityAssociationTableMap::DATABASE_NAME);
		foreach ($graphObjArray['data'] as $friend){
			// find whether there's already an association
			$sql = <<<EOT
			select 1 from user_community_assoc uca
				join user on uca.user_id_left = user.id
			    where user.fb_id = :friendfbid1
			    and uca.user_id_right = :curuserid1
			union
			select 1 from user_community_assoc uca
				join user on uca.user_id_right = user.id
			    where user.fb_id = :friendfbid2
			    and uca.user_id_left = :curuserid2;
EOT;
			$stmt = $conn->prepare($sql);
			$stmt->execute(
				array(
					':friendfbid1'	=> $friend->id,
					':curuserid1'	=> $curUser->getId(),
					':friendfbid2'	=> $friend->id,
					':curuserid2'	=> $curUser->getId()
				));
			
			// assess results
			$results = $stmt->fetchAll();
			
			// if not exist, add association
			if (count($results)==0){
				$sql = <<<EOT
				insert into user_community_assoc
					(user_id_left, user_id_right)
				select :curuserid, user.id
					from user
					where user.fb_id = :friendfbid;
EOT;
				$stmt = $conn->prepare($sql);
				$stmt->execute(
					array(
						':friendfbid'	=> $friend->id,
						':curuserid'	=> $curUser->getId()
					));
				
				
			}
		}
	}
	
	
	/**
	 * Verify that the two userIds are linked in the same community
	 * @param unknown $userId1
	 * @param unknown $userId2
	 * @return Return true if linked
	 */
	public static function verifyUsersAreFriends($userId1, $userId2){
		$conn = Propel::getReadConnection(UserCommunityAssociationTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select 1 from user_community_assoc
			    where user_id_left = :userid1
			    and user_id_right = :userid2
			union
			select 1 from user_community_assoc
			    where user_id_left = :userid3
			    and user_id_right = :userid4;
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
			array(
				':userid1'	=> $userId1,
				':userid2'	=> $userId2,
				':userid3'	=> $userId2,
				':userid4'	=> $userId1
			));
	
		// assess results
		$results = $stmt->fetchAll();
		return count($results)>=1;
	}
}
