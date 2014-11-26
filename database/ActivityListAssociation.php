<?php

use Base\ActivityListAssociation as BaseActivityListAssociation;
use Map\CategoryTableMap as CategoryTableMap;

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
	
	/**
	 * Get a list of categories that the activity associated with this list association object belongs to
	 * @return array of ActivityCategoryAssociation objects joined by Categories
	 */
	public function getActivityCategories(){
		return $actCatAssoc = ActivityCategoryAssociationQuery::create()
			->filterByActivityId($this->getActivityId())
			->joinCategory()
			->addAscendingOrderByColumn(CategoryTableMap::COL_NAME)
			->find();
	}
}
