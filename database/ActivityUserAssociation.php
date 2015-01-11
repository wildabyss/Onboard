<?php

use Base\ActivityUserAssociation as BaseActivityUserAssociation;

/**
 * Skeleton subclass for representing a row from the 'activity_user_assoc' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class ActivityUserAssociation extends BaseActivityUserAssociation
{
	const ACTIVE_STATUS = 1;
	const COMPLETED_STATUS = 2;
	const ARCHIVED_STATUS = 3;
	
	const USER_IS_NOT_ASSOCIATED = 0;
	const USER_IS_ASSOCIATED = 1;
	const USER_IS_OWNER =2;
	
	
	/**
	 * Get a list of categories that the activity associated with this user association object belongs to
	 * @return array of ActivityCategoryAssociation objects joined by Categories
	 */
	public function getActivityCategories(){
		return ActivityCategoryAssociationQuery::getActivityCategories($this->getActivityId());
	}
	
	
	/**
	 * Save the ActivityUserAssociation object with tagged categories
	 * @param ActivityUserAssociation $activityAssociationObj
	 * @param array of strings $rawCategories
	 * @param Propel\Runtime\Connection\ConnectionInterface $con
	 */
	public function saveWithCategories(ActivityUserAssociation $activityAssociationObj, $rawCategories,
			Propel\Runtime\Connection\ConnectionInterface $con=NULL) {
	
		// save ActivityUserAssociation object itself
		parent::save($con);
	
		// get existing categories
		$existingCategories = $activityAssociationObj->getActivityCategories();
	
		// see which categories to exclude, and which ones to investigate
		$rawCatsToExclude = array();
		foreach ($existingCategories as $existingCategoryAssocObj){
			$keep = false;
			foreach ($rawCategories as $rawCat){
				$rawCat = trim($rawCat);
				if ($rawCat == ""){
					$rawCatsToExclude[] = $rawCat;
				} elseif (strtoupper($rawCat) == strtoupper($existingCategoryAssocObj->getCategory()->getName())){
					$keep = true;
					$rawCatsToExclude[] = $rawCat;
					break;
				}
			}
	
			// if deemed not to keep, remove it permanently
			if (!$keep){
				$existingCategoryAssocObj->delete();
			}
		}
	
		// investigate whether the category should be created, or simply associated
		foreach ($rawCategories as $rawCat){
			$rawCat = trim($rawCat);
			if ($rawCat=="" || in_array($rawCat, $rawCatsToExclude))
				continue;
	
			$categoryObj = CategoryQuery::create()->findOneByName($rawCat);
			if (!$categoryObj){
				// create Category object
				$categoryObj = new Category();
				$categoryObj->setName($rawCat);
				$categoryObj->save($con);
			}
	
			// create ActivityCategoryAssociation
			$activityCatAssocObj = new ActivityCategoryAssociation();
			$activityCatAssocObj->setActivityId($activityAssociationObj->getActivityId());
			$activityCatAssocObj->setCategoryId($categoryObj->getId());
			$activityCatAssocObj->save($con);
		}
	}
}
