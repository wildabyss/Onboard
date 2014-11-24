<?php

use Base\UserQuery;
use Base\User as BaseUser;
use Propel\Runtime\Propel;
use Map\UserTableMap;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser
{
	/**
	 * Get this user's friends and return in the form of a short associative array
	 * @param Results to be returned &$results
	 *     Contains two associative indexes: id and display_name
	 */
	public function getFriends(&$results){
		$myId = $this->getPrimaryKey();
		$results = array();
		
		// search for friends
		$conn = Propel::getReadConnection(UserTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select distinct id, display_name from
				(select user.id, user.display_name 
					from user_community_assoc uca 
					join user user on uca.user_id_right = user.id 
					where uca.user_id_left = :myid1
				union
				select user.id, user.display_name 
					from user_community_assoc uca 
					join user user on uca.user_id_left = user.id 
					where uca.user_id_right = :myid2) d
			order by
				display_name;
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(array(':myid1' => $myId, ':myid2' => $myId));
		
		// populate results array
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$results[] = $row;
		}
	}
}
