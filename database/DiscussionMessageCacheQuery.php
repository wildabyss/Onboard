<?php

use Base\DiscussionMessageCacheQuery as BaseDiscussionMessageCacheQuery;
use Map\DiscussionMessageCacheTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for performing query and update operations on the 'discussion_msg_cache' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class DiscussionMessageCacheQuery extends BaseDiscussionMessageCacheQuery
{
	/**
	 * Get the content of the cached messages and return them as an associative array
	 * @param Propel\Runtime\Connection\ConnectionInterface $conn
	 * @return array: cache_id, discussion_msg, time, activity_user_assoc_id, discussion_id, file_name
	 */
	public static function getCachedMessages(ConnectionInterface $conn=null){
		if ($conn==null)
			$conn = Propel::getConnection(DiscussionMessageCacheTableMap::DATABASE_NAME);
		
		// generate request
		$sql = <<<EOT
			select 
				cache.id cache_id, cache.msg discussion_msg, 
				cache.time, dua.activity_user_assoc_id, 
				disc.id discussion_id, disc.file_name
			from 
				discussion_msg_cache cache
			left join
				discussion_user_assoc dua
			on
				dua.id = cache.discussion_user_assoc_id
			left join
				discussion disc
			on 
				disc.id = dua.discussion_id
			order by
				cache_id asc
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute();
			
		// return results
		return $stmt->fetchAll();
	}
	
	
	/**
	 * Delete all rows with ID less or equal to $maxId
	 * @param $maxId, the maximum ID to be deleted
	 * @param Propel\Runtime\Connection\ConnectionInterface $conn
	 * @return The number of rows affected
	 */
	public static function deleteCachedMessages($maxId, ConnectionInterface $conn=null){
		if ($conn==null)
			$conn = Propel::getConnection(DiscussionMessageCacheTableMap::DATABASE_NAME);
		
		// generate request
		$sql = <<<EOT
			delete from 
				discussion_msg_cache
			where
				id <= :maxid
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
			array(
				'maxid' => $maxId
			));
		
		return $stmt->rowCount();
	}
} // DiscussionMessageCacheQuery
