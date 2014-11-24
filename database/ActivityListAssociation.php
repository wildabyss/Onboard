<?php

use Base\ActivityListAssociation as BaseActivityListAssociation;

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
}
