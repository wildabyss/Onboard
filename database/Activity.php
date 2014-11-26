<?php

use Base\Activity as BaseActivity;
use Map\CategoryTableMap as CategoryTableMap;

/**
 * Skeleton subclass for representing a row from the 'activity' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Activity extends BaseActivity
{
	/**
	 * Get a list of categories that this activity is associated with
	 * @return array of ActivityCategoryAssociation objects joined by Categories
	 */
	public function getActivityCategories(){
		return $actCatAssoc = ActivityCategoryAssociationQuery::create()
			->filterByActivity($this)
			->joinCategory()
			->addAscendingOrderByColumn(CategoryTableMap::COL_NAME)
			->find();
	}
}
