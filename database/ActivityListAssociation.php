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
	
	
	/**
	 * Save the ActivityListAssociation object with tagged categories
	 * @param ActivityListAssociation $activityAssociationObj
	 * @param array of strings $rawCategories
	 * @param Propel\Runtime\Connection\ConnectionInterface $con
	 */
	public function saveWithCategories(ActivityListAssociation $activityAssociationObj, $rawCategories,
			Propel\Runtime\Connection\ConnectionInterface $con=NULL) {
	
		// save ActivityListAssociation object itself
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
