<?php

use Base\ActivityList as BaseActivityList;
use Map\ActivityListAssociationTableMap;

/**
 * Skeleton subclass for representing a row from the 'activity_list' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class ActivityList extends BaseActivityList
{
	public function getActiveOrCompletedActivityAssociations(){
		$criteria = $this->buildCriteria()
			->addOr(ActivityListAssociationTableMap::COL_STATUS, ActivityListAssociation::COMPLETED_STATUS)
			->addOr(ActivityListAssociationTableMap::COL_STATUS, ActivityListAssociation::ACTIVE_STATUS)
			->addAscendingOrderByColumn(ActivityListAssociationTableMap::COL_ALIAS);
		return $this->getActivityListAssociations($criteria);
	}
}
