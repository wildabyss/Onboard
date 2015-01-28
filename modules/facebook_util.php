<?php

use Facebook\GraphUser;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;

class FacebookUtilities {
	const FACEBOOK_PRIVILEGES = array("email","user_friends");
	
	// profile picture sizes
	const PROFILE_PIC_LARGE = 3;
	const PROFILE_PIC_SMALL = 4;
	
	
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
		$user = UserQuery::create()->findOneByFbId($fbId);
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
	public static function FinalizeFacebookLogin(FacebookSession $session, $curUserObj){
		$_SESSION['fb_token'] = $session->getToken();
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
	
	
	public static function GetProfilePicture(FacebookSession $session, User $user){
		// ignore https certificate
		$contextOptions = [
			'http' => [
					'header' => 'Connection: close\r\n'
			],
			'https' => [
				'header' => 'Connection: close\r\n'
			]
		];
		
		$context = stream_context_create(array('https' => array('header'=>'Connection: close\r\n')));
		
		// get large sized profile picture
		$request = new FacebookRequest($session, 'GET', '/me/picture', 
			array (
				'height' => '100',
				'type' => 'normal',
				'width' => '100',
				'redirect' => false
			)
		);
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
		
		// save large profile picture
		Utilities::SaveImage($graphObject->getProperty('url'), 'profile_pic_cache/'.$user->getId().'_large.jpg');
		
		// get small sized profile picture
		$request = new FacebookRequest($session, 'GET', '/me/picture',
			array (
				'height' => '50',
				'type' => 'normal',
				'width' => '50',
				'redirect' => false
			)
		);
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
		
		// save small profile picture
		Utilities::SaveImage($graphObject->getProperty('url'), 'profile_pic_cache/'.$user->getId().'_small.jpg');
	}
}