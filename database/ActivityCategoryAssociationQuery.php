<?php

use Base\ActivityCategoryAssociationQuery as BaseActivityCategoryAssociationQuery;
use Map\CategoryTableMap as CategoryTableMap;

/**
 * Skeleton subclass for performing query and update operations on the 'activity_category_assoc' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class ActivityCategoryAssociationQuery extends BaseActivityCategoryAssociationQuery
{
	/**
	 * Get a list of categories that this activity is associated with
	 * @param unknown $activityId
	 * @return array of ActivityCategoryAssociation objects joined by Categories
	 */
	public static function getActivityCategories($activityId){
		return $actCatAssoc = ActivityCategoryAssociationQuery::create()
			->filterByActivityId($activityId)
			->joinCategory()
			->addAscendingOrderByColumn(CategoryTableMap::COL_NAME)
			->find();
	}
}
