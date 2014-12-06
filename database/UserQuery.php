<?php

use Base\UserQuery as BaseUserQuery;
use Facebook\GraphUser;
use Facebook\FacebookRequest;

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
	/**
	 * Validate that the user has already registered with our app
	 * Return the User object if true, otherwise return false
	 * @param Facebook session $session
	 * @return User object|boolean
	 */
	public static function ValidateFacebookLogin($session){
		// retrieve Facebook ID
		$user_profile = (new FacebookRequest($session, 'GET', '/me'))
			->execute()->getGraphObject(GraphUser::className());
		$fbId = $user_profile->getId();
		
		// verify that the user is in our database
		$user = self::create()->findOneByFbId($fbId);
		if ($user){
			return $user;
		} else {
			return false;
		}
	}
	
	
	/**
	 * Finalize the login process by saving the Facebook session and the User object into
	 * PHP session
	 * @param Facebook session $session
	 * @param User $curUserObj
	 */
	public static function FinalizeFacebookLogin($session, $curUserObj){
		$_SESSION['fb_session'] = $session;
		$_SESSION['current_user'] = $curUserObj;
	}
	
	
	/**
	 * Log the current user out of the system
	 * Destroy session
	 */
	public static function Logout(){
		session_destroy();
	}
	
	
	/**
	 * Register the user referred to by the current Facebook session
	 * If successful, return the newly created User object
	 * @param Facebook session $session
	 * @return User|boolean
	 */
	public static function RegisterFacebookUser($session){
		// retrieve Facebook profile information
		$user_profile = (new FacebookRequest($session, 'GET', '/me'))
			->execute()->getGraphObject(GraphUser::className());
		$fbId = $user_profile->getId();
		$name = $user_profile->getName();
		$email = $user_profile->getEmail();
		
		// save the user into database
		$user = new User();
		$user->setFbId($fbId);
		$user->setDisplayName($name);
		$user->setEmail($email);
		$user->setDateJoined(time());
		$user->setStatus(User::ACTIVE_STATUS);
		if ($user->save()){
			// save a default activity list
			$actList = new ActivityList();
			$actList->setUser($user);
			$actList->setName(ActivityList::DEFAULT_LIST);
			$actList->save();
			
			return $user;
		}
		else
			return false;
	}

} // UserQuery
