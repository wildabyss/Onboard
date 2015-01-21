<?php

use Base\UserQuery as BaseUserQuery;
use Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\Formatter\ObjectFormatter;

/**
 * Skeleton subclass for performing query and update operations on the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class UserQuery extends BaseUserQuery
{
	public static function getUserForActivityUserAssociation($actUserAssocId){
		$conn = Propel::getReadConnection(UserTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select distinct user.*
				from user
				left join activity_user_assoc aua
				on aua.user_id = user.id
				where aua.id = :activityassoc
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
				array(
						'activityassoc'	=> $actUserAssocId
				));
		
		$formatter = new ObjectFormatter();
		$formatter->setClass('\User'); //full qualified class name
		$resultArr = $formatter->format($conn->getDataFetcher($stmt));
		
		if (count($resultArr)==1)
			return $resultArr[0];
		else
			return false;
	}

} // UserQuery
