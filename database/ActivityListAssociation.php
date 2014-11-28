<?php

use Base\ActivityListAssociationQuery;

use Base\DiscussionUserAssociationQuery;
use Base\ActivityListAssociation as BaseActivityListAssociation;
use Map\CategoryTableMap as CategoryTableMap;
use Map\UserTableMap as UserTableMap;
use Map\ActivityListTableMap as ActivityListTableMap;

/**
 * Skeleton subclass for representing a row from the 'activity_list_assoc' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class ActivityListAssociation extends BaseActivityListAssociation
{
	const ACTIVE_STATUS = 1;
	const COMPLETED_STATUS = 2;
	const ARCHIVED_STATUS = 3;
	
	const USER_IS_NOT_ASSOCIATED = 0;
	const USER_IS_ASSOCIATED = 1;
	const USER_IS_OWNER =2;
	
	/**
	 * Get a list of categories that the activity associated with this list association object belongs to
	 * @return array of ActivityCategoryAssociation objects joined by Categories
	 */
	public function getActivityCategories(){
		return ActivityCategoryAssociationQuery::getActivityCategories($this->getActivityId());
	}
	
	
	/**
	 * Get the user associated with this ActivityListAssociation object
	 * @return User
	 */
	public function getUser(){
		$list = $this->getActivityList();
		return $list->getUser();
		
		/*return $user = ActivityListAssociationQuery::create()
			->joinActivityList()
			->join(UserTableMap::COL_ID, UserQuery::LEFT_JOIN)
			->filterBy(ActivityListTableMap::COL_ID, $this->getListId())
			->findOne();*/
	}
}
